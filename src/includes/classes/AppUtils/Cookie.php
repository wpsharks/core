<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Encrypted cookie utilities.
 *
 * @since 150424 Initial release.
 */
class Cookie extends Classes\AbsBase
{
    /**
     * Sets a cookie.
     *
     * @param string      $name          Name of the cookie.
     * @param string      $value         Cookie value (empty to delete).
     * @param string      $key           Encryption key. See {@link Rijndael256}
     * @param int|null    $expires_after Time (in seconds) this cookie should last for.
     * @param string|null $domain        Domain name to set the cookie for.
     * @param string|null $path          Path to set the cookie for.
     */
    public function set(string $name, string $value, string $key, int $expires_after = null, string $domain = null, string $path = null)
    {
        if (headers_sent()) {
            throw new Exception('Doing it wrong! Headers sent already.');
        }
        if (!$name) {
            return; // Not possible.
        }
        if (isset($value[0])) { // Encrypt if not empty.
            $value = $this->Utils->Rijndael256->encrypt($value, $key);
        }
        $expires_after = max(0, $expires_after ?? 31556926);
        $expires       = $expires_after ? time() + $expires_after : 0;

        $domain = $domain ?? $this->Utils->UrlCurrent->host(true);
        $domain = $domain === 'root' ? '.'.$this->Utils->UrlCurrent->rootHost(true) : $domain;
        $path   = $path ?? '/'; // Default path covers the entire site.

        setcookie($name, $value, $expires, $path, $domain);

        if (mb_stripos($name, '_test_') === false) {
            $_COOKIE[$name] = $value; // Update in real-time.
        }
    }

    /**
     * Gets an encrypted cookie value.
     *
     * @param string $name Name of the cookie.
     * @param string $key  Encryption key. See {@link Rijndael256}
     *
     * @return string Cookie value (unencrypted).
     */
    public function get(string $name, string $key): string
    {
        if ($name && isset($_COOKIE[$name]) && is_string($_COOKIE[$name])) {
            return $this->Utils->Rijndael256->decrypt($_COOKIE[$name], $key);
        }
        return ''; // Missing cookie.
    }
}
