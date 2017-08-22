<?php
/**
 * Header utilities.
 *
 * @author @jaswrks
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
 * Header utilities.
 *
 * @since 151121 Header utilities.
 */
class Headers extends Classes\Core\Base\Core implements Interfaces\HttpStatusConstants
{
    /**
     * Current request headers.
     *
     * @since 17xxxx Current request headers.
     *
     * @return array Unique/associative array of all headers.
     */
    public function current(): array
    {
        if (($headers = &$this->cacheKey(__FUNCTION__)) !== null) {
            return $headers; // Cached this already.
        }
        $headers = []; // Initialize.

        foreach ($_SERVER as $_header => $_value) {
            if (mb_stripos($_header, 'HTTP_') === 0) {
                $_header                          = preg_replace('/^HTTP_/ui', '', $_header);
                $_header                          = str_replace('_', '-', $_header);
                $headers[mb_strtolower($_header)] = $_v;
            }
        } // unset($_header, $_value); // Housekeeping.

        return $headers;
    }

    /**
     * No-cache headers.
     *
     * @since 160118 Adding no-cache headers.
     */
    public function sendNoCache()
    {
        if (headers_sent()) {
            throw $this->c::issue('Headers already sent.');
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
     * Get slug for status code.
     *
     * @since 17xxxx Adding status slug utility.
     *
     * @param int $status The HTTP status code.
     *
     * @return string Status slug for code.
     */
    public function getStatusSlug(int $status): string
    {
        return c::nameToSlug($this->getStatusMessage($status));
    }

    /**
     * Get message for status code.
     *
     * @since 17xxxx Adding status message utility.
     *
     * @param int $status The HTTP status code.
     *
     * @return string Status message for code.
     */
    public function getStatusMessage(int $status): string
    {
        return $this::HTTP_STATUSES[$status] ?? 'Invalid request.';
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

        $errors_dir = $this->App->Config->©fs_paths['©errors_dir'];

        $display_error      = (bool) $args['display_error'];
        $display_error_page = (string) $args['display_error_page'];

        $message = $this->getStatusMessage($status);

        if (headers_sent()) {
            throw $this->c::issue('Headers already sent.');
        } elseif ($this->c::isCli()) {
            throw $this->c::issue('Not possible in CLI mode.');
        }
        if (!($protocol = $_SERVER['SERVER_PROTOCOL'] ?? '')) {
            $protocol = 'HTTP/1.1'; // Default fallback.
        }
        header($protocol.' '.$status.' '.$message, true, $status);

        if ($status >= 400 && $display_error) {
            if ($errors_dir && $display_error_page) {
                $error_page = $errors_dir.'/'.$status.'/'.$display_error_page.'/index.html';
            } elseif ($errors_dir) {
                $error_page = $errors_dir.'/'.$status.'/index.html';
            } else {
                $error_page = ''; // Not possible.
            }
            $this->c::noCacheFlags();
            $this->c::sessionWriteClose();
            $this->c::obEndCleanAll();

            $this->sendNoCache(); // No-cache headers.
            header('content-type: text/html; charset=utf-8');

            if ($error_page && is_file($error_page)) {
                readfile($error_page);
            } else {
                echo '<h1>'.$this->c::escHtml($message).'</h1>';
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
