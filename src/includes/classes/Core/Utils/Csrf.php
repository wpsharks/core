<?php
/**
 * CSRF utils.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * CSRF utils.
 *
 * @since 17xxxx CSRF utils.
 */
class Csrf extends Classes\Core\Base\Core
{
    /**
     * CSRF generator.
     *
     * @since 17xxxx CSRF utils.
     *
     * @param callable|null $callback Callback.
     *
     * @return string New, unique CSRF token.
     *                CaSe-insensitive, 32 bytes in length.
     *
     * @note Callback stores the token for future verification.
     * If no callback, a `$_SESSION` storage handler is used by default.
     */
    public function create(callable $callback = null): string
    {
        $_csrf = $this->c::uuidV4();

        if (isset($callback)) {
            $callback($_csrf);
            return $_csrf;
        }
        if ($this->c::isActiveSession()) {
            return $_SESSION['_csrf'] = $_csrf;
        }
        throw $this->c::issue('No storage handler.');
    }

    /**
     * CSRF input markup.
     *
     * @since 17xxxx CSRF utils.
     *
     * @param callable|null $callback Callback.
     *
     * @return string Hidden input field with `_csrf` token.
     *                See also: {@link create()}
     */
    public function input(callable $callback = null): string
    {
        $_csrf = $this->create($callback);
        return '<input type="hidden" name="_csrf" value="'.$this->c::escAttr($_csrf).'" />';
    }

    /**
     * CSRF verifier.
     *
     * @since 17xxxx CSRF utils.
     *
     * @param string|null   $_csrf    Token.
     * @param callable|null $callback Callback.
     *
     * @return bool True if token is verified.
     *
     * @note Callback verifies the CSRF token.
     * If no callback, `$_SESSION` is used by default.
     */
    public function verify(string $_csrf = null, callable $callback = null): bool
    {
        if (!isset($_csrf)) {
            $_csrf = (string) $_REQUEST['_csrf'];
        } // Defaults to current request.

        if ($this->c::isActiveSession()) {
            if (empty($_SESSION['_csrf'])) {
                return false; // No token.
            }
            return $this->c::hashEquals($_SESSION['_csrf'], $_csrf);
        }
        throw $this->c::issue('No verification handler.');
    }
}
