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
 * @since 17xxxx Fatalities.
 */
class Fatalities extends Classes\Core\Base\Core
{
    /**
     * Die (general).
     *
     * @since 17xxxx Fatalities.
     *
     * @param string $message Custom message.
     * @param string $slug    Custom error slug.
     * @param string $code    Custom error status code.
     */
    public function die(string $message = '', string $slug = '', int $code = 0)
    {
        $code    = $code ?: 500;
        $slug    = $slug ?: 'internal';
        $message = $message ?: __('Internal server error.');

        $this->c::obEndCleanAll();
        $this->c::noCacheHeaders();
        $this->c::noCacheFlags();

        if ($this->c::isAjax() || $this->c::isApi()) {
            $this->c::statusHeader($code);
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
            $this->c::statusHeader($code);
            header('content-type: text/html; charset=utf-8');
            exit($this->c::getTemplate('http/html/utils/fatality.php')->parse(compact('message')));
        }
    }

    /**
     * Die (as an echo).
     *
     * @since 17xxxx Fatalities.
     */
    public function dieEcho()
    {
        $this->c::obEndCleanAll();
        $this->c::noCacheFlags();
        $this->c::noCacheHeaders();

        $url     = $this->c::currentUrl();
        $method  = $this->c::currentMethod();
        $headers = $this->c::currentHeaders();

        $get_vars     = $this->c::unslash($_GET);
        $request_vars = $_r = $this->c::unslash($_REQUEST);

        $raw_data  = (string) file_get_contents('php://input');
        $json_data = $raw_data ? (json_decode($raw_data) ?: null) : null;

        $code    = (int) ($_r['status'] ?? $_r['code'] ?? 200);
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

                'method'   => $method,
                'url'      => $url,
                'get_vars' => $get_vars,

                'data' => [
                    'vars' => $request_vars,
                    'raw'  => $raw_data,
                    'json' => $json_data,
                ],
                'status' => [
                    'code'    => $code,
                    'slug'    => $slug,
                    'message' => $message,
                ],
            ],
        ]), JSON_PRETTY_PRINT);

        $this->c::statusHeader($code);

        if ($this->c::isAjax() || $this->c::isApi()) {
            header('content-type: application/json; charset=utf-8');
            exit($response);
        } else {
            header('content-type: text/plain; charset=utf-8');
            exit($response);
        }
    }

    /**
     * Die (invalid).
     *
     * @since 17xxxx Fatalities.
     *
     * @param string $message Custom message.
     * @param string $slug    Custom error slug.
     * @param string $code    Custom error status code.
     */
    public function dieInvalid(string $message = '', string $slug = '', int $code = 0)
    {
        $this->die($message ?: __('Invalid request.'), $slug ?: 'invalid', $code ?: 400);
    }

    /**
     * Die (forbidden).
     *
     * @since 17xxxx Fatalities.
     *
     * @param string $message Custom message.
     * @param string $slug    Custom error slug.
     * @param string $code    Custom error status code.
     */
    public function dieForbidden(string $message = '', string $slug = '', int $code = 0)
    {
        $this->die($message ?: __('Forbidden.'), $slug ?: 'forbidden', $code ?: 403);
    }
}
