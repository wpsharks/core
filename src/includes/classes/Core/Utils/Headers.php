<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Header utilities.
 *
 * @since 151121 Header utilities.
 */
class Headers extends Classes\Core\Base\Core implements Interfaces\HttpStatusConstants
{
    /**
     * No-cache headers.
     *
     * @since 160118 Adding no-cache headers.
     */
    public function sendNoCache()
    {
        if (headers_sent()) {
            throw new Exception('Headers already sent.');
        }
        $headers = [
            'expires'       => 'wed, 11 jan 1984 05:00:00 gmt',
            'cache-control' => 'no-cache, must-revalidate, max-age=0',
            'pragma'        => 'no-cache',
        ];
        foreach ($headers as $_header => $_value) {
            header($_header.': '.$_value);
        } // unset($_header, $_value);

        header_remove('last-modified');
    }

    /**
     * Status header.
     *
     * @since 151121 Header utilities.
     *
     * @param int   $status The HTTP status code.
     * @param array $args   Any additional behavioral args.
     */
    public function sendStatus(int $status, array $args = [])
    {
        $default_args = [
            'display_error'      => false,
            'display_error_page' => 'default',
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        $display_error      = (bool) $args['display_error'];
        $display_error_page = (string) $args['display_error_page'];
        $errors_dir         = $this->App->Config->©fs_paths['©errors_dir'];

        if (headers_sent()) {
            throw new Exception('Headers already sent.');
        } elseif (empty($this::HTTP_STATUSES[$status])) {
            throw new Exception('Unknown status.');
        } elseif ($this->c::isCli()) {
            throw new Exception('Not possible in CLI mode.');
        }
        if (!($protocol = $_SERVER['SERVER_PROTOCOL'] ?? '')) {
            $protocol = 'HTTP/1.1'; // Default fallback.
        }
        header($protocol.' '.$status.' '.$this::HTTP_STATUSES[$status], true, $status);

        if ($status >= 400 && $display_error) {
            if ($errors_dir && $display_error_page) {
                $error_page = $errors_dir.'/'.$status.'/'.$display_error_page.'/index.html';
            } elseif ($errors_dir) {
                $error_page = $errors_dir.'/'.$status.'/index.html';
            } else {
                $error_page = ''; // No errors directory.
            }
            $this->sendNoCache(); // Don't cache errors.
            header('content-type: text/html; charset=utf-8');

            if ($error_page && is_file($error_page)) {
                readfile($error_page);
            } else {
                echo '<h1>'.$this->c::escHtml($this::HTTP_STATUSES[$status]).'</h1>';
            }
        }
    }

    /**
     * Parses headers.
     *
     * @since 151121 Header utilities.
     *
     * @param mixed $value Input value w/ headers.
     *
     * @return array Unique/associative array of all headers.
     */
    public function parse($value): array
    {
        $headers = []; // Initialize.

        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => $_value) {
                if ($_key && is_string($_key) && is_string($_value)) {
                    $headers = array_merge($headers, $this->parse($_key.': '.$_value));
                } else {
                    $headers = array_merge($headers, $this->parse($_value));
                }
            } // unset($_key, $_value);
            return $headers; // All done here.
        }
        $string = (string) $value; // Force string.

        foreach (explode("\r\n", $string) as $_rn_delimited_header) {
            if (mb_strpos($_rn_delimited_header, ':') === false) {
                continue; // Invalid header.
            }
            list($_header, $_value) = $this->c::mbTrim(explode(':', $_rn_delimited_header, 2));

            if (!$_header || !isset($_value[0])) {
                continue; // Invalid header.
            }
            $headers[mb_strtolower($_header)] = $_value;
        } // unset($_rn_delimited_header, $_header, $_value);

        return $headers;
    }
}
