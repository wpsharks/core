<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * URL remote utilities.
 *
 * @since 150424 Initial release.
 */
class UrlRemote extends Classes\AbsBase
{
    /**
     * Remote HTTP communication.
     *
     * @param string $url  A URL to connect to.
     * @param array  $args Connection arguments.
     *
     * @return string|array Based on arguments.
     */
    public function request(string $url, array $args = [])
    {
        return $this->requestCurl($url, $args);
    }

    /**
     * cURL for remote HTTP communication.
     *
     * @param string $url  A URL to connect to.
     * @param array  $args Connection arguments.
     *
     * @return string|array Based on arguments.
     */
    protected function requestCurl(string $url, array $args = [])
    {
        # Parse arguments.

        $default_args = [
            'headers'         => [],
            'body'            => '',
            'cookie_file'     => '',
            'max_redirects'   => 5,
            'max_con_secs'    => 20,
            'max_stream_secs' => 20,
            'fail_on_error'   => true,
            'return_array'    => false,
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        # Extract and sanitize all args.

        extract($args); // Typify args.
        $headers         = (array) $headers;
        $cookie_file     = (string) $cookie_file;
        $max_redirects   = max(0, (int) $max_redirects);
        $max_con_secs    = max(1, (int) $max_con_secs);
        $max_stream_secs = max(1, (int) $max_stream_secs);
        $fail_on_error   = (bool) $fail_on_error;
        $return_array    = (bool) $return_array;

        # Parse the URL for a possible request method; e.g., `POST::`.

        $custom_request_method        = ''; // Initialize.
        $custom_request_methods       = ['HEAD','GET','POST','PUT','PATCH','DELETE'];
        $custom_request_methods_regex = '/^(?<method>(?:'.implode('|', $custom_request_methods).'))\:{2}(?<url>.+)/ui';

        if (preg_match($custom_request_methods_regex, $url, $_m)) {
            $url                   = $_m['url']; // URL after `::`.
            $custom_request_method = mb_strtoupper($_m['method']);

            if ($custom_request_method === 'HEAD') {
                $return_array = true;
            }
        } // unset($_m); // Housekeeping.

        # Validate URL.

        if (!$url) { // Failure.
            return $return_array ? [] : '';
        }
        # Convert body to a string.

        if (is_array($body) || is_object($body)) {
            $body = $this->Utils->UrlQuery->build((array) $body);
        } else {
            $body = (string) $body;
        }
        # Make sure we always have a `user-agent`.

        foreach ($headers as $_key => $_header) {
            if (mb_stripos($_header, 'user-agent:') === 0) {
                $has_user_agent = true;
            }
        } // unset($_key, $_header); // Housekeeping.

        if (empty($has_user_agent)) {
            $headers[]      = 'user-agent: '.__METHOD__;
            $has_user_agent = true; // Does now!
        }
        # Setup header collection sub-routine.

        $curl_headers           = ''; // Initialize.
        $collect_output_headers = function ($curl, $headers) use (&$curl_headers) {
            $curl_headers .= $headers; // Write.
            return strlen($headers); // Bytes written.
        };
        # Determine if we can follow location headers.

        $follow_location = $max_redirects > 0
            && !filter_var(ini_get('safe_mode'), FILTER_VALIDATE_BOOLEAN)
            && !ini_get('open_basedir');
        $max_redirects = !$follow_location ? 0 : $max_redirects;

        # Configure cURL options.

        $curl_opts = [
            CURLOPT_URL            => $url,
            CURLOPT_CONNECTTIMEOUT => $max_con_secs,
            CURLOPT_TIMEOUT        => $max_stream_secs,

            CURLOPT_ENCODING   => '',
            CURLOPT_HTTPHEADER => $headers,

            CURLOPT_FAILONERROR    => $fail_on_error,
            CURLOPT_FOLLOWLOCATION => $follow_location,
            CURLOPT_MAXREDIRS      => $max_redirects,

            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HEADERFUNCTION => $collect_output_headers,
            CURLOPT_NOBODY         => $custom_request_method === 'HEAD',
        ];
        if ($body && $custom_request_method !== 'HEAD') {
            if ($custom_request_method) {
                $curl_opts += [CURLOPT_CUSTOMREQUEST => $custom_request_method, CURLOPT_POSTFIELDS => $body];
            } else {
                $curl_opts += [CURLOPT_POST => true, CURLOPT_POSTFIELDS => $body];
            }
        } elseif ($custom_request_method) {
            $curl_opts += [CURLOPT_CUSTOMREQUEST => $custom_request_method];
        }
        if ($cookie_file) {
            $curl_opts += [CURLOPT_COOKIEJAR => $cookie_file, CURLOPT_COOKIEFILE => $cookie_file];
        }
        # Perform HTTP request(s).

        $curl = curl_init(); // Initialize.
        curl_setopt_array($curl, $curl_opts);
        $curl_body = $this->Utils->Trim((string) curl_exec($curl));

        # Collect cURL info after request is complete.

        $curl_info = curl_getinfo($curl); // All information.
        $curl_info = is_array($curl_info) ? $curl_info : []; // Empty.
        $curl_code = isset($curl_info['http_code']) ? (int) $curl_info['http_code'] : 0;

        # Parse the headers that we collected, if any.

        $curl_headers = explode("\r\n\r\n", $this->Utils->Trim($curl_headers));
        $curl_headers = $curl_headers[count($curl_headers) - 1];
        // ↑ Last set of headers; in case of location redirects.

        $_curl_headers = $curl_headers;
        $curl_headers  = []; // Initialize.

        foreach (preg_split('/['."\r\n".']+/u', $_curl_headers, -1, PREG_SPLIT_NO_EMPTY) as $_line) {
            if (isset($_line[0]) && mb_strpos($_line, ':', 1) !== false) {
                list($_header, $_value)                                    = explode(':', $_line, 2);
                $curl_headers[mb_strtolower($this->Utils->Trim($_header))] = $this->Utils->Trim($_value);
            }
        } // unset($_curl_headers, $_line, $_header, $_value); // Housekeeping.

        # Check if failing on error. If so, empty the response body.

        if ($fail_on_error && ($curl_code === 0 || $curl_code >= 400)) {
            $curl_body = ''; // Empty body in case of error.
        }
        # Close cURL resource handle.

        curl_close($curl); // Close cURL resource now.

        # Return final response; array or just the body.

        return $return_array ? [
            'code'    => $curl_code,
            'headers' => $curl_headers,
            'body'    => $curl_body,
        ] : $curl_body;
    }
}
