<?php
/**
 * Cookie utilities.
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
 * Cookie utilities.
 *
 * @since 150424 Initial release.
 */
class Cookie extends Classes\Core\Base\Core
{
    /**
     * Class constructor.
     *
     * @since 150424 Initial release.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        if ($this->c::isCli()) {
            throw $this->c::issue('Not possible in CLI mode.');
        }
    }

    /**
     * Sets a cookie.
     *
     * @param string      $name          Name of the cookie.
     * @param string      $value         Cookie value (empty to delete).
     * @param int|null    $expires_after Time (in seconds) this cookie should last for.
     * @param string|null $path          Path to set the cookie for.
     * @param string|null $domain        Domain name to set the cookie for.
     * @param bool|null   $secure        Over SSL access only?
     * @param bool|null   $http_only     HTTP only access?
     * @param string|null $key           Encryption key.
     */
    public function set(
        string $name,
        string $value,
        int $expires_after = null,
        string $path = null,
        string $domain = null,
        bool $secure = null,
        bool $http_only = null,
        string $key = null
    ) {
        if (!$name) { // Must have a cookie name!
            throw $this->c::issue('Missing cookie name.');
            //
        } elseif (!$key && !($key = $this->App->Config->©cookies['©encryption_key'])) {
            throw $this->c::issue('Missing cookie encryption key.');
        }
        if (isset($value[0])) { // If not empty.
            $value = $this->c::encrypt($value, $key);
        }
        $expires_after = max(0, $expires_after ?? 31556926);
        $expires       = $expires_after ? time() + $expires_after : 0;

        $path   = $path ?? '/'; // Entire site.
        $domain = $domain ?? $this->c::currentHost(false);
        $domain = $domain === 'root' ? '.'.$this->c::currentRootHost(false) : $domain;

        $secure    = $secure ?? $this->c::isSsl();
        $http_only = $http_only ?? true;

        if (headers_sent()) {
            throw $this->c::issue('Headers already sent.');
        }
        setcookie($name, $value, $expires, $path, $domain, $secure, $http_only);

        if (mb_stripos($name, '_test_') === false) {
            $_COOKIE[$name] = $value;
        }
    }

    /**
     * Sets an unencrypted cookie.
     *
     * @param string      $name          Name of the cookie.
     * @param string      $value         Cookie value (empty to delete).
     * @param int|null    $expires_after Time (in seconds) this cookie should last for.
     * @param string|null $path          Path to set the cookie for.
     * @param string|null $domain        Domain name to set the cookie for.
     * @param bool|null   $secure        Over SSL access only?
     * @param bool|null   $http_only     HTTP only access?
     */
    public function setUe(
        string $name,
        string $value,
        int $expires_after = null,
        string $path = null,
        string $domain = null,
        bool $secure = null,
        bool $http_only = null
    ) {
        if (!$name) { // Must have a cookie name!
            throw $this->c::issue('Missing cookie name.');
        }
        $expires_after = max(0, $expires_after ?? 31556926);
        $expires       = $expires_after ? time() + $expires_after : 0;

        $path   = $path ?? '/'; // Entire site.
        $domain = $domain ?? $this->c::currentHost(false);
        $domain = $domain === 'root' ? '.'.$this->c::currentRootHost(false) : $domain;

        $secure    = $secure ?? $this->c::isSsl();
        $http_only = $http_only ?? true;

        if (headers_sent()) {
            throw $this->c::issue('Headers already sent.');
        }
        setcookie($name, $value, $expires, $path, $domain, $secure, $http_only);

        if (mb_stripos($name, '_test_') === false) {
            $_COOKIE[$name] = $value;
        }
    }

    /**
     * Gets an encrypted cookie value.
     *
     * @param string      $name Name of the cookie.
     * @param string|null $key  Encryption key.
     *
     * @return string Cookie value (decrypted).
     */
    public function get(string $name, string $key = null): string
    {
        if (!$name) { // Must have a cookie name!
            throw $this->c::issue('Missing cookie name.');
            //
        } elseif (!$key && !($key = $this->App->Config->©cookies['©encryption_key'])) {
            throw $this->c::issue('Missing cookie encryption key.');
        }
        if (isset($_COOKIE[$name]) && is_string($_COOKIE[$name])) {
            return $this->c::decrypt($_COOKIE[$name], $key);
        }
        return ''; // Missing cookie.
    }

    /**
     * Gets an unencrypted cookie value.
     *
     * @param string $name Name of the cookie.
     *
     * @return string Cookie value.
     */
    public function getUe(string $name): string
    {
        if (!$name) { // Must have a cookie name!
            throw $this->c::issue('Missing cookie name.');
        }
        if (isset($_COOKIE[$name]) && is_string($_COOKIE[$name])) {
            return $_COOKIE[$name];
        }
        return ''; // Missing cookie.
    }
}
