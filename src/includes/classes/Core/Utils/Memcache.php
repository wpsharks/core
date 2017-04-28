<?php
/**
 * Memcache utilities.
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
 * Memcache utilities.
 *
 * @since 151216 Memcache utils.
 */
class Memcache extends Classes\Core\Base\Core
{
    /**
     * Pool.
     *
     * @since 151216
     *
     * @type \Memcached
     */
    protected $Pool;

    /**
     * Enable?
     *
     * @since 151216
     *
     * @type bool
     */
    protected $enabled;

    /**
     * Namespace.
     *
     * @since 151216
     *
     * @type string
     */
    protected $namespace;

    /**
     * Servers.
     *
     * @since 151216
     *
     * @type array
     */
    protected $servers;

    /**
     * Max attempts.
     *
     * @since 151216
     *
     * @type int
     */
    const MAX_WRITE_ATTEMPTS = 5;

    /**
     * Class constructor.
     *
     * @since 151216 Memcache utils.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        if (!($extension_loaded = extension_loaded('memcached'))) {
            $this->enabled = false; // Not possible.
            return; // Not possible; extension missing.
        }
        $this->enabled = $this->App->Config->©memcache['©enabled'];
        $this->enabled = $this->enabled ?? $extension_loaded;

        $this->namespace = $this->App->Config->©memcache['©namespace'];
        $this->servers   = $active_servers   = []; // Initialize.

        foreach ($this->App->Config->©memcache['©servers'] as $_server) {
            $_host                            = $_server['©host'];
            $_port                            = $_server['©port'] ?? 11211;
            $_weight                          = $_server['©weight'] ?? 0;
            $this->servers[$_host.':'.$_port] = [$_host, $_port, $_weight];
        } // unset($_server, $_host, $_port, $_weight);

        if (!$this->enabled || !$this->namespace || !$this->servers) {
            $this->enabled = false; // Not possible.
            return; // Disabled or lacking config values.
        }
        try { // Catch exceptions on init and fail gracefully.
            // This also covers a scenario where the extension is loaded in PHP,
            // but the `memcached` binary is not actually available on the server.
            $this->Pool = new \Memcached($this->App->namespace_sha1);
            $this->maybeAddServerConnections();
            //
        } catch (\Throwable $Exception) {
            $this->enabled = false; // Not possible.
            $this->c::issue(vars(), 'Memcached init failure. '.$Exception->getMessage());
        }
    }

    /**
     * Memcache enabled?
     *
     * @since 170215.53419 Memcache utils.
     *
     * @return bool True if enabled.
     */
    public function enabled(): bool
    {
        return $this->enabled;
    }

    /**
     * Memcache info.
     *
     * @since 170215.53419 Memcache utils.
     *
     * @return array Stats/keys/etc.
     */
    public function info(): array
    {
        if (!$this->enabled) {
            return []; // Not possible.
        }
        return [
            'stats' => $this->Pool->getStats() ?: [],
            'keys'  => $this->Pool->getAllKeys() ?: [],
            // NOTE: `getAllKeys()` returns `false` on some servers.
            // There is a bug in the libmemcached library that makes it unreliable.
            // See: <https://github.com/php-memcached-dev/php-memcached/issues/203>
        ];
    }

    /**
     * Get cache value by key.
     *
     * @since 151216 Memcache utils.
     *
     * @param string     $primary_key Primary key.
     * @param string|int $sub_key     Sub-key to get.
     *
     * @return mixed|null Null if missing, or on failure.
     */
    public function get(string $primary_key, $sub_key)
    {
        if (!$this->enabled) {
            return; // Not possible.
        } elseif (!($key = $this->key($primary_key, $sub_key))) {
            return; // Fail; e.g., race condition.
        }
        $value = $this->Pool->get($key); // Possibly `false`.
        // See: <http://php.net/manual/en/memcached.get.php#92275>

        if ($this->Pool->getResultCode() === \Memcached::RES_SUCCESS) {
            return $value; // Return the value.
        }
    }

    /**
     * Set|update cache key.
     *
     * @since 151216 Memcache utils.
     *
     * @param string     $primary_key Primary key.
     * @param string|int $sub_key     Sub-key to set.
     * @param string     $value       Value to cache (1MB max).
     * @param int        $expires_in  Expires (in seconds).
     *
     * @return bool True on success.
     */
    public function set(string $primary_key, $sub_key, $value, int $expires_in = 0): bool
    {
        if (!$this->enabled) {
            return false; // Not possible.
        }
        $expires_in = max(0, $expires_in);
        $expires    = $expires_in ? time() + $expires_in : 0;

        if (!($key = $this->key($primary_key, $sub_key))) {
            return false; // Fail; e.g., race condition.
        } elseif ($value === null || is_resource($value)) {
            throw $this->c::issue('Incompatible data type.');
        }
        do { // Avoid race issues.
            $cas      = $cas ?? 0;
            $attempts = $attempts ?? 0;
            ++$attempts; // Counter.

            $this->Pool->get($key, null, $cas);

            if ($this->Pool->getResultCode() === \Memcached::RES_NOTFOUND) {
                if ($this->Pool->add($key, $value, $expires)) {
                    return true; // All good; stop here.
                }
            } elseif ($this->Pool->cas($cas, $key, $value, $expires)) {
                return true; // All good; stop here.
            }
            $result_code = $this->Pool->getResultCode();
            //
        } while ($attempts < $this::MAX_WRITE_ATTEMPTS // Give up after X attempts.
                && ($result_code === \Memcached::RES_NOTSTORED || $result_code === \Memcached::RES_DATA_EXISTS));

        return false; // Fail; e.g., race condition or unexpected error.
    }

    /**
     * Touch a cache key (i.e., new expiration).
     *
     * @since 151216 Memcache utils.
     *
     * @param string     $primary_key Primary key.
     * @param string|int $sub_key     Sub-key to touch.
     * @param int        $expires_in  Expires (in seconds).
     *
     * @return bool True on success.
     */
    public function touch(string $primary_key, $sub_key, int $expires_in): bool
    {
        if (!$this->enabled) {
            return false; // Not possible.
        }
        $expires_in = max(0, $expires_in);
        $expires    = $expires_in ? time() + $expires_in : 0;

        if (!($key = $this->key($primary_key, $sub_key))) {
            return false; // Fail; e.g., race condition.
        }
        return $this->Pool->touch($key, $expires);
    }

    /**
     * Clear the cache.
     *
     * @since 151216 Memcache utils.
     *
     * @param string          $primary_key Primary key.
     * @param string|int|null $sub_key     Sub-key (optional).
     * @param int             $delay       Delay (in seconds).
     *
     * @return bool True on success.
     */
    public function clear(string $primary_key, $sub_key = null, int $delay = 0): bool
    {
        if (!$this->enabled) {
            return false; // Not possible.
        }
        if (!isset($sub_key)) {
            $key = $this->nspKey($primary_key);
        } else {
            $key = $this->key($primary_key, $sub_key);
        }
        return $key && $this->Pool->delete($key, $delay);
    }

    /**
     * Namespace a key.
     *
     * @since 151216 Memcache utils.
     *
     * @param string     $primary_key Primary key.
     * @param string|int $sub_key     Sub-key.
     *
     * @return string Full namespaced cache key.
     */
    protected function key(string $primary_key, $sub_key): string
    {
        if (!$this->enabled) {
            return ''; // Not possible.
        } elseif (!($namespaced_primary_key = $this->nspKey($primary_key))) {
            return ''; // Not possible; e.g., empty key.
        }
        $namespaced_primary_key_uuid = ''; // Initialize.

        do { // Avoid race issues.
            $attempts = $attempts ?? 0;
            ++$attempts; // Counter.

            if (($existing_namespaced_primary_key_uuid = (string) $this->Pool->get($namespaced_primary_key))) {
                $namespaced_primary_key_uuid = $existing_namespaced_primary_key_uuid;
                break; // All good; stop here.
            }
            if (!isset($new_namespaced_primary_key_uuid)) {
                $new_namespaced_primary_key_uuid = $this->c::uuidV4();
            }
            if ($this->Pool->add($namespaced_primary_key, $new_namespaced_primary_key_uuid)) {
                $namespaced_primary_key_uuid = $new_namespaced_primary_key_uuid;
                break; // All good; stop here.
            }
            $result_code = $this->Pool->getResultCode();
            //
        } while ($attempts < $this::MAX_WRITE_ATTEMPTS && $result_code === \Memcached::RES_NOTSTORED);

        if (!$namespaced_primary_key_uuid) {
            return ''; // Failure; e.g., race condition.
        }
        $sub_key                            = (string) $sub_key;
        $namespaced_primary_key_uuid_prefix = $namespaced_primary_key_uuid.'\\';
        $namespaced_key                     = $namespaced_primary_key_uuid_prefix.$sub_key;

        if (isset($namespaced_key[251])) {
            throw $this->c::issue(sprintf('Sub key too long; %1$s bytes max.', 250 - strlen($namespaced_primary_key_uuid_prefix)));
        }
        return $namespaced_key;
    }

    /**
     * Namespace primary key.
     *
     * @since 151216 Memcache utils.
     *
     * @param string $primary_key Primary key.
     *
     * @return string Namespaced primary key.
     */
    protected function nspKey(string $primary_key): string
    {
        if (!$this->enabled) {
            return ''; // Not possible.
        } elseif (!isset($primary_key[0])) {
            return ''; // Not possible.
        }
        $namespace_prefix       = 'x___'.$this->namespace.'\\';
        $namespaced_primary_key = $namespace_prefix.$primary_key;

        if (isset($namespaced_primary_key[251])) {
            throw $this->c::issue(sprintf('Primary key too long; %1$s bytes max.', 250 - strlen($namespace_prefix)));
        }
        return $namespaced_primary_key;
    }

    /**
     * Servers differ? i.e., current vs. active.
     *
     * @since 151216 Memcache utils.
     *
     * @return bool True if servers differ.
     */
    protected function serversDiffer(): bool
    {
        if (!$this->enabled) {
            return false; // Not possible.
        }
        $active_servers = []; // Initialize.

        foreach ($this->Pool->getServerList() as $_server) {
            $active_servers[$_server['host'].':'.$_server['port']] = $_server;
        } // unset($_server); // Housekeeping.

        if (count($this->servers) !== count($active_servers)) {
            return true; // They definitely differ.
        }
        foreach ($this->servers as $_key => $_server) {
            if (!isset($active_servers[$_key])) {
                return true;
            } // unset($_key, $_server);
        }
        foreach ($active_servers as $_key => $_server) {
            if (!isset($this->servers[$_key])) {
                return true;
            } // unset($_key, $_server);
        }
        return false;
    }

    /**
     * Maybe add server connections.
     *
     * @since 151216 Memcache utils.
     */
    protected function maybeAddServerConnections()
    {
        if (!$this->enabled) {
            return; // Not possible.
        }
        if ($this->serversDiffer()) {
            $this->Pool->quit();
            $this->Pool->resetServerList();

            $this->Pool->setOption(\Memcached::OPT_NO_BLOCK, true);
            $this->Pool->setOption(\Memcached::OPT_LIBKETAMA_COMPATIBLE, true);

            if (\Memcached::HAVE_IGBINARY) { // Size and speed gains.
                $this->Pool->setOption(\Memcached::OPT_BINARY_PROTOCOL, true);
                $this->Pool->setOption(\Memcached::OPT_SERIALIZER, \Memcached::SERIALIZER_IGBINARY);
            }
            $this->Pool->setOption(\Memcached::OPT_CONNECT_TIMEOUT, 1000);
            $this->Pool->setOption(\Memcached::OPT_POLL_TIMEOUT, 1000);
            $this->Pool->setOption(\Memcached::OPT_RETRY_TIMEOUT, 1);

            $this->Pool->setOption(\Memcached::OPT_SEND_TIMEOUT, 1000000);
            $this->Pool->setOption(\Memcached::OPT_RECV_TIMEOUT, 1000000);

            $this->Pool->addServers($this->servers);
        }
    }
}
