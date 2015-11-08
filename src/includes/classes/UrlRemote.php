<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * URL remote utilities.
 *
 * @since 150424 Initial release.
 */
class UrlRemote extends AbsBase
{
    protected $Trim;
    protected $PhpHas;
    protected $UrlQuery;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(
        Trim $Trim,
        PhpHas $PhpHas,
        UrlQuery $UrlQuery
    ) {
        parent::__construct();

        $this->Trim     = $Trim;
        $this->PhpHas   = $PhpHas;
        $this->UrlQuery = $UrlQuery;
    }

    /**
     * Remote HTTP communication.
     *
     * @param string $url  A URL to connect to.
     * @param array  $args Connection arguments; i.e., new behavior.
     *
     * @return string|array Output based on configured arguments.
     */
    public function request(string $url, array $args = array())
    {
        if (!$this->PhpHas->extension('curl')) {
            throw new Exception('cURL extension missing.');
        }
        if (!$this->PhpHas->extension('openssl')) {
            throw new Exception('OpenSSL extension missing.');
        }
        if (!is_array($curl = curl_version()) || !($curl['features'] & CURL_VERSION_SSL)) {
            throw new Exception('cURL not compiled w/ OpenSSL.');
        }
        return $this->requestCurl($url, $args);
    }

    /**
     * cURL for remote HTTP communication.
     *
     * @param string $url  A URL to connect to.
     * @param array  $args Connection arguments; i.e., new behavior.
     *
     * @return string|array Output based on configured arguments.
     */
    public function requestCurl(string $url, array $args = array())
    {
        # Parse arguments.

        $default_args = [
            'body' => '',

            'max_con_secs'    => 20,
            'max_stream_secs' => 20,

            'headers' => array(),

            'cookie_file' => '',

            'fail_on_error' => true,
            'return'        => $this::STRING_TYPE,
            // Or `$this::ARRAY_A_TYPE` alternative.
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        # Extract and sanitize all args.

        $custom_request_method = '';
        $body                  = $args['body'];
        $max_con_secs          = (integer) $args['max_con_secs'];
        $max_stream_secs       = (integer) $args['max_stream_secs'];
        $headers               = (array) $args['headers'];
        $cookie_file           = (string) $args['cookie_file'];
        $fail_on_error         = (boolean) $args['fail_on_error'];
        $return                = (string) $args['return'];

        # Parse the URL for a possible request method; e.g., `POST::`.

        $custom_request_methods       = ['HEAD','GET','POST','PUT','PATCH','DELETE'];
        $custom_request_methods_regex = // e.g.,`HEAD::http://www.example.com/path/to/my.php`
            '/^(?P<method>(?:'.implode('|', $custom_request_methods).'))\:{2}(?P<url>.+)/ui';

        if (preg_match($custom_request_methods_regex, $url, $_m)) {
            $url                   = $_m['url']; // URL after `::`.
            $custom_request_method = mb_strtoupper($_m['method']);

            if ($custom_request_method === 'HEAD') {
                $return = $this::ARRAY_A_TYPE;
            }
        } // unset($_m); // Housekeeping.

        # Validate URL.

        if (!$url) { // Failure.
            return $return === $this::ARRAY_A_TYPE ? [] : '';
        }
        # Convert body to a string.

        if (is_array($body) || is_object($body)) {
            $body = $this->UrlQuery->build((array) $body);
        } else {
            $body = (string) $body;
        }
        # Make sure we always have a `User-Agent`.

        foreach ($headers as $_header) {
            if (mb_stripos($_header, 'User-Agent:') === 0) {
                $has_user_agent = true;
            }
        }
        unset($_header); // Housekeeping.

        if (empty($has_user_agent)) {
            $headers[]      = 'User-Agent: '.__METHOD__;
            $has_user_agent = true; // Does now!
        }
        # Setup header collection sub-routine.

        $curl_headers           = ''; // Initialize.
        $collect_output_headers = function ($curl, $headers) use (&$curl_headers) {
            $curl_headers .= $headers; // Write.
            return strlen($headers); // Bytes written.
        };
        # Determine if we can follow location headers.

        $can_follow = !filter_var(ini_get('safe_mode'), FILTER_VALIDATE_BOOLEAN)
                      && !ini_get('open_basedir'); // Possible?

        # Configure cURL options.

        $curl_opts = [
            CURLOPT_URL            => $url,
            CURLOPT_HTTPHEADER     => $headers,
            CURLOPT_CONNECTTIMEOUT => $max_con_secs,
            CURLOPT_TIMEOUT        => $max_stream_secs,

            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false,
            CURLOPT_HEADERFUNCTION => $collect_output_headers,
            CURLOPT_NOBODY         => $custom_request_method === 'HEAD',

            CURLOPT_FOLLOWLOCATION => $can_follow,
            CURLOPT_MAXREDIRS      => $can_follow ? 5 : 0,

            CURLOPT_ENCODING       => '',
            CURLOPT_VERBOSE        => false,
            CURLOPT_FAILONERROR    => $fail_on_error,
            CURLOPT_SSL_VERIFYPEER => false,
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
        $curl_body = $this->Trim((string) curl_exec($curl));

        # Collect cURL info after request is complete.

        $curl_info = curl_getinfo($curl); // All information.
        $curl_info = is_array($curl_info) ? $curl_info : []; // Empty.
        $curl_code = isset($curl_info['http_code']) ? (integer) $curl_info['http_code'] : 0;

        # Parse the headers that we collected, if any.

        $curl_headers = explode("\r\n\r\n", $this->Trim($curl_headers));
        $curl_headers = $curl_headers[count($curl_headers) - 1];
        // ↑ Last set of headers; in case of location redirects.

        $_curl_headers = $curl_headers; // Temp.
        $curl_headers  = []; // Initialize array.

        foreach (preg_split('/['."\r\n".']+/u', $_curl_headers, -1, PREG_SPLIT_NO_EMPTY) as $_line) {
            if (isset($_line[0]) && mb_strpos($_line, ':', 1) !== false) {
                list($_header, $_value)                             = explode(':', $_line, 2);
                $curl_headers[mb_strtolower($this->Trim($_header))] = $this->Trim($_value);
            }
        } // unset($_curl_headers, $_line, $_header, $_value); // Housekeeping.

        # Check if failing on error. If so, empty the response body.

        if ($fail_on_error && ($curl_code === 0 || $curl_code >= 400)) {
            $curl_body = ''; // Empty body in case of error.
        }
        # Close cURL resource handle.

        curl_close($curl); // We can close the cURL resource handle now.

        # Return final response; either an array or just the response body.

        if ($return === $this::ARRAY_A_TYPE) {
            return [
                'code'    => $curl_code,
                'headers' => $curl_headers,
                'body'    => $curl_body,
            ];
        }
        return $curl_body;
    }
}
