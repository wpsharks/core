<?php
namespace WebSharks\Core\Classes\Utils;

/**
 * Encrypted cookie utilities.
 *
 * @since 150424 Initial release.
 */
class EncCookieUtils extends AbsBase
{
    protected $UrlCurrentUtils;
    protected $EncRij256Utils;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(
        UrlCurrentUtils $UrlCurrentUtils,
        EncRij256Utils $EncRij256Utils
    ) {
        parent::__construct();

        $this->UrlCurrentUtils = $UrlCurrentUtils;
        $this->EncRij256Utils  = $EncRij256Utils;
    }

    /**
     * Sets an encrypted cookie.
     *
     * @param string $name          Name of the cookie.
     * @param string $value         Value for this cookie (empty to delete).
     * @param string $key           Optional. Key to use in cookie encryption.
     * @param int    $expires_after Optional. Time (in seconds) this cookie should last for.
     *                              Defaults to `31556926` (one year). If this is set to anything <= `0`,
     *                              the cookie will expire automatically after the current browser session.
     *
     * @throws \Exception If headers have already been sent; i.e. if not possible.
     */
    public function encCookieSet($name, $value, $key = '', $expires_after = 31556926)
    {
        if (headers_sent()) {
            throw new \Exception('Doing it wrong! Headers sent already.');
        }
        if (!($name = trim((string) $name))) {
            return; // Not possible.
        }
        $value         = (string) $value;
        $expires_after = max(0, (integer) $expires_after);
        $key           = (string) $key; // Encryption key.

        $value   = $value ? $this->EncRij256Utils->encRij256Encrypt($value, $key) : '';
        $expires = $expires_after ? time() + $expires_after : 0;

        $cookie_path      = defined('COOKIEPATH') ? (string) COOKIEPATH : '/';
        $site_cookie_path = defined('SITECOOKIEPATH') ? (string) SITECOOKIEPATH : '/';
        $cookie_domain    = defined('COOKIE_DOMAIN') ? (string) COOKIE_DOMAIN : $this->UrlCurrentUtils->urlCurrentHost(true);

        setcookie($name, $value, $expires, $cookie_path, $cookie_domain);
        setcookie($name, $value, $expires, $site_cookie_path, $cookie_domain);

        if (stripos($name, 'test_') !== 0 && (!defined('TEST_COOKIE') || $name !== TEST_COOKIE)) {
            $_COOKIE[$name] = $value; // Update in real-time.
        }
    }

    /**
     * Gets an encrypted cookie value.
     *
     * @param string $name Name of the cookie.
     * @param string $key  Optional. Key originally used in encryption.
     *
     * @return string Cookie string value (unencrypted).
     */
    public function encCookieGet($name, $key = '')
    {
        if (!($name = (string) $name)) {
            return ''; // Not possible.
        }
        $key = (string) $key; // Encryption key.

        if (isset($_COOKIE[$name][0]) && is_string($_COOKIE[$name])) {
            $value = $this->EncRij256Utils->encRij256Decrypt($_COOKIE[$name], $key);
        }
        return isset($value[0]) ? $value : '';
    }
}
