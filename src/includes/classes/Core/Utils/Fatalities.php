<?php
/**
 * Fatalities.
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
 * Fatalities.
 *
 * @since 170824.30708 Fatalities.
 */
class Fatalities extends Classes\Core\Base\Core
{
    /**
     * Die (general).
     *
     * @since 170824.30708 Fatalities.
     *
     * @param string|int $message Message (or code).
     * @param string     $slug    Custom error slug.
     * @param string     $code    Custom error status code.
     */
    public function die($message = '', string $slug = '', int $code = 500)
    {
        if (is_int($message)) {
            $code    = $message;
            $message = '';
        }
        $code    = $code ?: 500; // Default code.
        $slug    = $slug ?: $this->c::statusHeaderSlug($code);
        $message = $message ?: $this->c::statusHeaderMessage($code);

        $this->c::obEndCleanAll();
        $this->c::noCacheFlags();
        $this->c::noCacheHeaders();
        $this->c::statusHeader($code);

        if ($this->c::isAjax() || $this->c::isApi()) {
            header('content-type: application/json; charset=utf-8');
            exit(json_encode([
                'success' => false,
                'error'   => [
                    'code'    => $code,
                    'slug'    => $slug,
                    'message' => $message,
                ],
            ]));
        } else {
            header('content-type: text/html; charset=utf-8');
            exit($this->c::getTemplate('http/html/utils/fatality.php')->parse(compact('message')));
        }
    }

    /**
     * Die (invalid).
     *
     * @since 170824.30708 Fatalities.
     *
     * @param string $message Custom message.
     * @param string $slug    Custom error slug.
     * @param string $code    Custom error status code.
     */
    public function dieInvalid(string $message = '', string $slug = '', int $code = 400)
    {
        $this->die($message, $slug, $code);
    }

    /**
     * Die (forbidden).
     *
     * @since 170824.30708 Fatalities.
     *
     * @param string $message Custom message.
     * @param string $slug    Custom error slug.
     * @param string $code    Custom error status code.
     */
    public function dieForbidden(string $message = '', string $slug = '', int $code = 403)
    {
        $this->die($message, $slug, $code);
    }

    /**
     * Die (as an echo).
     *
     * @since 170824.30708 Fatalities.
     */
    public function dieEcho()
    {
        $url     = $this->c::currentUrl();
        $method  = $this->c::currentMethod();
        $headers = $_h = $this->c::currentHeaders();

        $query = $this->c::unslash($_GET);
        $vars  = $_r  = $this->c::unslash($_REQUEST);

        $raw = (string) file_get_contents('php://input');

        if (mb_stripos($_h['content-type'] ?? '', '/json')) {
            $json = json_decode($raw) ?: (object) [];
        }
        $code    = (int) ($_r['status'] ?? 200);
        $slug    = $this->c::statusHeaderSlug($code);
        $message = $this->c::statusHeaderMessage($code);

        $response = json_encode($this->c::removeNulls([
            'success' => $code < 400,

            'error'   => $code >= 400 ? [
                'code'    => $code,
                'slug'    => $slug,
                'message' => $message,
            ] : null,

            'echo'    => [
                'time' => time(),

                'method' => $method,
                'url'    => $url,
                'query'  => $query,

                'headers' => $headers,
                'data'    => [
                    'vars' => $vars,
                    'json' => $json ?? null,
                    'raw'  => $raw,
                ],
                'status' => [
                    'code'    => $code,
                    'slug'    => $slug,
                    'message' => $message,
                ],
            ],
        ]), JSON_PRETTY_PRINT);

        $this->c::obEndCleanAll();
        $this->c::noCacheFlags();
        $this->c::noCacheHeaders();
        $this->c::statusHeader($code);

        if ($this->c::isAjax() || $this->c::isApi()) {
            header('content-type: application/json; charset=utf-8');
            exit($response);
        } else {
            header('content-type: text/plain; charset=utf-8');
            exit($response);
        }
    }
}
