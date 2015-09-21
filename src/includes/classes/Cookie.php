<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * Encrypted cookie utilities.
 *
 * @since 150424 Initial release.
 */
class Cookie extends AbsBase
{
    protected $UrlCurrent;
    protected $Rijndael256;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(
        UrlCurrent $UrlCurrent,
        Rijndael256 $Rijndael256
    ) {
        parent::__construct();

        $this->UrlCurrent  = $UrlCurrent;
        $this->Rijndael256 = $Rijndael256;
    }

    /**
     * Sets an encrypted cookie.
     *
     * @param string $name          Name of the cookie.
     * @param string $value         Value for this cookie (empty to delete).
     * @param int    $expires_after Optional. Time (in seconds) this cookie should last for.
     *                              Defaults to `31556926` (one year). If this is set to anything <= `0`,
     *                              the cookie will expire automatically after the current browser session.
     * @param string $domain        Optional input domain name to set the cookie for.
     * @param string $key           Optional. Key to use in cookie encryption.
     *
     * @throws \Exception If headers have already been sent; i.e. if not possible.
     */
    public function set(string $name, string $value, int $expires_after = 31556926, string $domain = '', string $key = '')
    {
        if (headers_sent()) {
            throw new \Exception('Doing it wrong! Headers sent already.');
        }
        if (!($name = trim($name))) {
            return; // Not possible.
        }
        $expires_after = max(0, $expires_after);
        $value         = $value ? $this->Rijndael256->encrypt($value, $key) : '';
        $expires       = $expires_after ? time() + $expires_after : 0;

        if (defined('COOKIE_PATH')) {
            $cookie_path = (string) COOKIE_PATH;
        } elseif (defined('COOKIEPATH')) {
            $cookie_path = (string) COOKIEPATH;
        } elseif (!empty($_SERVER['COOKIE_PATH'])) {
            $cookie_path = (string) $_SERVER['COOKIE_PATH'];
        } else {
            $cookie_path = '/';
        }
        if (defined('SITE_COOKIE_PATH')) {
            $site_cookie_path = (string) SITE_COOKIE_PATH;
        } elseif (defined('SITECOOKIEPATH')) {
            $site_cookie_path = (string) SITECOOKIEPATH;
        } elseif (!empty($_SERVER['SITE_COOKIE_PATH'])) {
            $site_cookie_path = (string) $_SERVER['SITE_COOKIE_PATH'];
        } else {
            $site_cookie_path = '/';
        }
        if ($domain) {
            $cookie_domain = $domain;
        } elseif (defined('COOKIE_DOMAIN')) {
            $cookie_domain = (string) COOKIE_DOMAIN;
        } elseif (defined('COOKIEDOMAIN')) {
            $cookie_domain = (string) COOKIEDOMAIN;
        } elseif (!empty($_SERVER['COOKIE_DOMAIN'])) {
            $cookie_domain = (string) $_SERVER['COOKIE_DOMAIN'];
        } else {
            $cookie_domain = $this->UrlCurrent->host(true);
        }
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
    public function get(string $name, string $key = ''): string
    {
        if (!($name = trim($name))) {
            return ''; // Not possible.
        }
        if (isset($_COOKIE[$name][0]) && is_string($_COOKIE[$name])) {
            $value = $this->Rijndael256->decrypt($_COOKIE[$name], $key);
        }
        return isset($value[0]) ? $value : '';
    }
}
