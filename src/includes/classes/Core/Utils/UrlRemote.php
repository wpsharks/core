<?php
/**
 * URL remote utilities.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * URL remote utilities.
 *
 * @since 150424 Initial release.
 */
class UrlRemote extends Classes\Core\Base\Core
{
    /**
     * Remote HTTP communication.
     *
     * @param string $url  A URL to connect to.
     * @param array  $args Connection arguments.
     *
     * @return string|\StdClass|array Based on arguments.
     */
    public function request(string $url, array $args = [])
    {
        return $this->curl($url, $args);
    }

    /**
     * Remote HTTP communication.
     *
     * @param string $url  A URL to connect to.
     * @param array  $args Connection arguments.
     *
     * @return \StdClass Always returns a full response.
     */
    public function response(string $url, array $args = []): \StdClass
    {
        return $this->curl($url, array_merge($args, ['return' => 'object']));
    }

    /**
     * cURL for remote HTTP communication.
     *
     * @since 17xxxx Replaces `requestCurl()`.
     * @since 17xxxx Deprecated `return_array`.
     *
     * @param string $url  A URL to connect to.
     * @param array  $args Connection arguments.
     *
     * @return string|\StdClass|array Based on arguments.
     *
     * @TODO Break this apart into multiple solo routines.
     */
    protected function curl(string $url, array $args = [])
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
            'return_array'    => false, // Deprecated.
            'return'          => '', // Use this instead.
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        if (!empty($args['return_array']) && empty($args['return'])) {
            $args['return'] = 'array'; // Back compat.
        } // NOTE: Please use `return` instead of `return_array`.
        unset($args['return_array']); // Ditch this old argument now.

        # Extract and sanitize all args.

        extract($args); // Typify args.
        $headers         = (array) $headers;
        $cookie_file     = (string) $cookie_file;
        $max_redirects   = max(0, (int) $max_redirects);
        $max_con_secs    = max(1, (int) $max_con_secs);
        $max_stream_secs = max(1, (int) $max_stream_secs);
        $fail_on_error   = (bool) $fail_on_error;
        $return          = (string) $return;

        # Parse request method from URL; e.g., `POST::`

        $custom_request_method = ''; // Initialize, parsed below.
        static $custom_request_methods_regex; // Static cache.

        if (!isset($custom_request_methods_regex)) {
            $_custom_request_methods       = ['HEAD', 'GET', 'POST', 'PUT', 'PATCH', 'DELETE'];
            $custom_request_methods_regex  = '/^(?<method>(?:'.implode('|', $_custom_request_methods).'))\:{2}(?<url>.+)/ui';
        }
        if (mb_stripos($url, '::') !== false && preg_match($custom_request_methods_regex, $url, $_m)) {
            $url                   = $_m['url']; // URL after `::`.
            $custom_request_method = mb_strtoupper($_m['method']);

            if ($custom_request_method === 'HEAD' && !$return) {
                $return = 'array'; // Default for `HEAD` requests.
            } // This preserves back compat, otherwise it would be `object`.
        } // unset($_m, $_custom_request_methods); // Housekeeping.

        # Validate URL.

        if (!$url) { // Failure.
            $response = [
                'code'    => 0,
                'headers' => [],
                'body'    => '',
            ];
            if ($return === 'object') {
                return (object) $response;
            } elseif ($return === 'array') {
                return $response;
            } else { // Body only.
                return $response['body'];
            } // Default return type is `string`.
        }
        # Convert body to a string.

        if (is_array($body) || is_object($body)) {
            $body = $this->c::buildUrlQuery((array) $body);
        } else {
            $body = (string) $body;
        }
        # Make sure we always have a `user-agent`.

        foreach ($headers as $_key => $_header) {
            if (mb_stripos($_header, 'user-agent:') === 0) {
                $_has_user_agent = true;
                break; // Stop here.
            }
        } // unset($_key, $_header); // Housekeeping.

        if (empty($_has_user_agent)) {
            $headers[]      = 'user-agent: '.__METHOD__;
        }
        # Setup header collection sub-routine.

        $curl_headers           = ''; // Initialize.
        $collect_output_headers = function ($curl, $headers) use (&$curl_headers) {
            $curl_headers .= $headers; // Write.
            return strlen($headers); // Bytes written.
        };
        # Determine if we can follow location headers.

        static $can_follow_location = // Static cache of this detection.
            !filter_var(ini_get('safe_mode'), FILTER_VALIDATE_BOOLEAN) && !ini_get('open_basedir');

        $follow_location = $max_redirects > 0 && $can_follow_location;
        $max_redirects   = !$follow_location ? 0 : $max_redirects;

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
        $curl_body = (string) curl_exec($curl);
        // Note: Do not trim body as it can cause problems.
        // e.g., If a request is made for the purpose of generating a hash.

        # Collect cURL info after request is complete.

        $curl_info = curl_getinfo($curl); // All information.
        $curl_info = is_array($curl_info) ? $curl_info : []; // Empty.
        $curl_code = isset($curl_info['http_code']) ? (int) $curl_info['http_code'] : 0;

        # Parse the headers that we collected, if any.

        $curl_headers = explode("\r\n\r\n", $this->c::mbTrim($curl_headers));
        $curl_headers = $curl_headers[count($curl_headers) - 1];
        // ↑ Last set of headers; in case of location redirects.

        $_curl_headers = $curl_headers;
        $curl_headers  = []; // Initialize.

        foreach (preg_split('/['."\r\n".']+/u', $_curl_headers, -1, PREG_SPLIT_NO_EMPTY) as $_line) {
            if (isset($_line[0]) && mb_strpos($_line, ':', 1) !== false) {
                list($_header, $_value)                                  = explode(':', $_line, 2);
                $curl_headers[mb_strtolower($this->c::mbTrim($_header))] = $this->c::mbTrim($_value);
            }
        } // unset($_curl_headers, $_line, $_header, $_value); // Housekeeping.

        # Check if failing on error. If so, empty the response body.

        if ($fail_on_error && ($curl_code === 0 || $curl_code >= 400)) {
            $curl_body = ''; // Empty body in case of error.
        }
        # Close cURL resource handle.

        curl_close($curl); // Close cURL resource.

        # Maybe review HTTP connection data.

        if ($this->App->Config->©debug['©log']) {
            $this->c::review([
                'url'      => $url,
                'args'     => $args,
                'response' => [
                    'code'    => $curl_code,
                    'headers' => $curl_headers,
                    'body'    => $curl_body,
                ],
            ], 'Remote URL connection details.', __METHOD__.'#http');
        }
        # Return final response.

        $response = [
            'code'    => $curl_code,
            'headers' => $curl_headers,
            'body'    => $curl_body,
        ];
        if ($return === 'object') {
            return (object) $response;
        } elseif ($return === 'array') {
            return $response;
        } else { // Body only.
            return $response['body'];
        } // Default return type is `string`.
    }
}
