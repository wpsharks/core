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
        $code    = $code ?: 503;
        $slug    = $slug ?: 'internal';
        $message = $message ?: __('Internal server error.');

        $this->c::obEndCleanAll();
        $this->c::statusHeader($code);
        $this->c::noCacheHeaders();
        $this->c::noCacheFlags();

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
     * @since 17xxxx Fatalities.
     *
     * @param string $message Custom message.
     * @param string $slug    Custom error slug.
     * @param string $code    Custom error status code.
     */
    public function dieInvalid(string $message = '', string $slug = '', int $code = 0)
    {
        $this->die($message ?: __('Bad request.'), $slug ?: 'invalid', $code ?: 400);
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
