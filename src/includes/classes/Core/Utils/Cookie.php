<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function get_defined_vars as vars;

/**
 * Encrypted cookie utilities.
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
            throw new Exception('Not possible in CLI mode.');
        }
    }

    /**
     * Sets a cookie.
     *
     * @param string      $name          Name of the cookie.
     * @param string      $value         Cookie value (empty to delete).
     * @param int|null    $expires_after Time (in seconds) this cookie should last for.
     * @param string|null $domain        Domain name to set the cookie for.
     * @param string|null $path          Path to set the cookie for.
     * @param string      $key           Encryption key.
     */
    public function set(string $name, string $value, int $expires_after = null, string $domain = null, string $path = null, string $key = '')
    {
        if (!$name) { // Must have a cookie name!
            throw new Exception('Missing cookie name.');
        }
        if (!$key && !($key = $this->App->Config->©cookies['©encryption_key'])) {
            throw new Exception('Missing cookie encryption key.');
        }
        if (isset($value[0])) { // If not empty.
            $value = $this->c::encrypt($value, $key);
        }
        $expires_after = max(0, $expires_after ?? 31556926);
        $expires       = $expires_after ? time() + $expires_after : 0;

        $domain = $domain ?? $this->c::currentHost(true);
        $domain = $domain === 'root' ? '.'.$this->c::currentRootHost(true) : $domain;
        $path   = $path ?? '/'; // Default path covers the entire site.

        if (headers_sent()) {
            throw new Exception('Headers already sent.');
        }
        setcookie($name, $value, $expires, $path, $domain);

        if (mb_stripos($name, '_test_') === false) {
            $_COOKIE[$name] = $value;
        }
    }

    /**
     * Gets an encrypted cookie value.
     *
     * @param string $name Name of the cookie.
     * @param string $key  Encryption key.
     *
     * @return string Cookie value (unencrypted).
     */
    public function get(string $name, string $key = ''): string
    {
        if (!$name) { // Must have a cookie name!
            throw new Exception('Missing cookie name.');
        }
        if (!$key && !($key = $this->App->Config->©cookies['©encryption_key'])) {
            throw new Exception('Missing cookie encryption key.');
        }
        if (isset($_COOKIE[$name]) && is_string($_COOKIE[$name])) {
            return $this->c::decrypt($_COOKIE[$name], $key);
        }
        return ''; // Missing cookie.
    }
}
