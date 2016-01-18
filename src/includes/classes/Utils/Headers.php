<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Header utilities.
 *
 * @since 151121 Header utilities.
 */
class Headers extends Classes\AppBase implements Interfaces\HttpStatusConstants
{
    /**
     * Status header.
     *
     * @since 151121 Header utilities.
     *
     * @param int $status The HTTP status code.
     */
    public function sendStatus(int $status)
    {
        if (headers_sent()) {
            throw new Exception('Headers already sent.');
        } elseif (empty($this::HTTP_STATUSES[$status])) {
            throw new Exception('Unknown status.');
        } elseif (c\is_cli()) {
            throw new Exception('Not possible in CLI mode.');
        }
        if (!($protocol = $_SERVER['SERVER_PROTOCOL'] ?? '')) {
            $protocol = 'HTTP/1.1'; // Default fallback.
        }
        header($protocol.' '.$status.' '.$this::HTTP_STATUSES[$status], true, $status);
    }

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
            'expires'       => 'Wed, 11 Jan 1984 05:00:00 GMT',
            'cache-control' => 'no-cache, must-revalidate, max-age=0',
            'pragma'        => 'no-cache',
        ];
        foreach ($headers as $_header => $_value) {
            header($_header.': '.$_value);
        } // unset($_header, $_value);

        header_remove('last-modified');
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
            list($_header, $_value) = c\mb_trim(explode(':', $_rn_delimited_header, 2));

            if (!$_header || !isset($_value[0])) {
                continue; // Invalid header.
            }
            $headers[mb_strtolower($_header)] = $_value;
        } // unset($_rn_delimited_header, $_header, $_value);

        return $headers;
    }
}
