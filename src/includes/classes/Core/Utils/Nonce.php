<?php
/**
 * Nonce utils.
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
 * Nonce utils.
 *
 * @since 17xxxx Nonce utils.
 */
class Nonce extends Classes\Core\Base\Core implements Interfaces\SecondConstants
{
    /**
     * Nonce generator.
     *
     * @since 17xxxx Nonce utils.
     *
     * @param string|null   $action   Action.
     * @param callable|null $callback Callback.
     *
     * @return string New, unique nonce token.
     *                CaSe-insensitive, 32 bytes in length.
     *
     * @note Callback stores the token for future verification.
     * If no callback, a `$_SESSION` storage handler is used by default.
     */
    public function create(string $action = null, callable $callback = null): string
    {
        $Nonce = (object) [
            'token'       => $this->c::uuidV4(),
            'action'      => mb_strtolower($action ?? ''),
            'expire_time' => time() + $this::SECONDS_IN_DAY,
            'used'        => false, // Unused on creation.
        ];
        if (isset($callback)) {
            $callback($Nonce);
            return $Nonce->token;
        }
        if ($this->c::isActiveSession()) {
            $_SESSION['_nonce'] = $_SESSION['_nonce'] ?? [];
            $this->c::arrayUnshiftAssoc($_SESSION['_nonce'], $Nonce->token, $Nonce);
            $_SESSION['_nonce'] = array_slice($_SESSION['_nonce'], 0, 64, true);
            return $Nonce->token;
        }
        throw $this->c::issue('No storage handler.');
    }

    /**
     * Nonce input markup.
     *
     * @since 17xxxx Nonce utils.
     *
     * @param string|null   $action   Action.
     * @param callable|null $callback Callback.
     *
     * @return string Hidden input field with `_nonce` token.
     *                See also: {@link create()}
     */
    public function input(string $action = null, callable $callback = null): string
    {
        $token = $this->create($action, $callback);
        return '<input type="hidden" name="_nonce" value="'.$this->c::escAttr($token).'" />';
    }

    /**
     * Nonce verifier.
     *
     * @since 17xxxx Nonce utils.
     *
     * @param string|null   $action   Action.
     * @param string|null   $token    Token.
     * @param callable|null $callback Callback.
     *
     * @return bool True if token is verified.
     *
     * @note Callback verifies token and flags token as having been used once before.
     * Or, if there is no callback, a built-in `$_SESSION` storage handler is used by default.
     */
    public function verify(string $action = null, string $token = null, callable $callback = null): bool
    {
        if (!isset($token)) {
            $token = (string) $_REQUEST['_nonce'];
        } // Defaults to current request.

        $action = mb_strtolower($action ?? '');
        $token  = mb_strtolower($token);

        if (isset($callback)) {
            return $callback($action, $token);
        }
        if ($this->c::isActiveSession()) {
            if (!isset($_SESSION['_nonce'][$token])) {
                return false; // Bad token.
            } // Reference Nonce in session.
            $Nonce = &$_SESSION['_nonce'][$token];

            if ($action !== $Nonce->action) {
                return false;
            } elseif (time() >= $Nonce->expire_time) {
                return false;
            } elseif ($Nonce->used) {
                return false;
            }
            return $Nonce->used = true;
        }
        throw $this->c::issue('No verification handler.');
    }
}
