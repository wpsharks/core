<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Memcache utilities.
 *
 * @since 151216 Memcached utilities.
 */
class Memcache extends Classes\Core
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
     * @since 151216 Memcached utilities.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        $this->enabled   = $this->App->Config->©memcache['©enabled'];
        $this->namespace = $this->App->Config->©memcache['©namespace'];
        $this->servers   = $active_servers   = []; // Initialize.

        foreach ($this->App->Config->©memcache['©servers'] as $_server) {
            $_host                            = $_server['host'];
            $_port                            = $_server['port'] ?? 11211;
            $_weight                          = $_server['weight'] ?? 0;
            $this->servers[$_host.':'.$_port] = [$_host, $_port, $_weight];
        } // unset($_server, $_host, $_port, $_weight);

        if (!class_exists('Memcached')) {
            return; // Not possible.
        } elseif (!$this->enabled || !$this->namespace || !$this->servers) {
            return; // Disabled or lacking config values.
        }
        $this->Pool = new \Memcached($this->App->namespace_sha1);

        $this->Pool->setOption(\Memcached::OPT_NO_BLOCK, true);
        $this->Pool->setOption(\Memcached::OPT_SEND_TIMEOUT, 5);
        $this->Pool->setOption(\Memcached::OPT_RECV_TIMEOUT, 5);

        $this->Pool->setOption(\Memcached::OPT_LIBKETAMA_COMPATIBLE, true);

        if (\Memcached::HAVE_IGBINARY) { // Size and speed gains.
            $this->Pool->setOption(\Memcached::OPT_BINARY_PROTOCOL, true);
            $this->Pool->setOption(\Memcached::OPT_SERIALIZER, \Memcached::SERIALIZER_IGBINARY);
        }
        $this->maybeAddServerConnections();
    }

    /**
     * Get cache value by key.
     *
     * @since 151216 Memcached utilities.
     *
     * @param string     $primary_key Primary key.
     * @param string|int $sub_key     Sub-key to get.
     *
     * @return mixed|null Null if missing, or on failure.
     */
    public function get(string $primary_key, $sub_key)
    {
        if (!$this->Pool) {
            return; // Not possible.
        }
        if (!($key = $this->key($primary_key, $sub_key))) {
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
     * @since 151216 Memcached utilities.
     *
     * @param string     $primary_key   Primary key.
     * @param string|int $sub_key       Sub-key to set.
     * @param string     $value         Value to cache (1MB max).
     * @param int        $expires_after Expires after (in seconds).
     *
     * @return bool True on success.
     */
    public function set(string $primary_key, $sub_key, $value, int $expires_after = 0): bool
    {
        if (!$this->Pool) {
            return false; // Not possible.
        }
        $time          = time();
        $expires_after = max(0, $expires_after);
        $expires       = $expires_after ? $time + $expires_after : 0;

        if (!($key = $this->key($primary_key, $sub_key))) {
            return false; // Fail; e.g., race condition.
        } elseif ($value === null || is_resource($value)) {
            throw new Exception('Incompatible data type.');
        }
        do { // Avoid race issues with a loop.

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
     * @since 151216 Memcached utilities.
     *
     * @param string     $primary_key   Primary key.
     * @param string|int $sub_key       Sub-key to touch.
     * @param int        $expires_after Expires after (in seconds).
     *
     * @return bool True on success.
     */
    public function touch(string $primary_key, $sub_key, int $expires_after): bool
    {
        if (!$this->Pool) {
            return false; // Not possible.
        }
        $time          = time();
        $expires_after = max(0, $expires_after);
        $expires       = $expires_after ? $time + $expires_after : 0;

        if (!($key = $this->key($primary_key, $sub_key))) {
            return false; // Fail; e.g., race condition.
        }
        return $this->Pool->touch($key, $expires);
    }

    /**
     * Clear the cache.
     *
     * @since 151216 Memcached utilities.
     *
     * @param string          $primary_key Primary key.
     * @param string|int|null $sub_key     Sub-key (optional).
     * @param int             $delay       Delay (in seconds).
     *
     * @return bool True on success.
     */
    public function clear(string $primary_key, $sub_key = null, int $delay = 0): bool
    {
        if (!$this->Pool) {
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
     * @since 151216 Memcached utilities.
     *
     * @param string     $primary_key Primary key.
     * @param string|int $sub_key     Sub-key.
     *
     * @return string Full namespaced cache key.
     */
    protected function key(string $primary_key, $sub_key): string
    {
        if (!$this->Pool) {
            return ''; // Not possible.
        }
        if (!($namespaced_primary_key = $this->nspKey($primary_key))) {
            return ''; // Not possible; e.g., empty key.
        }
        $namespaced_primary_key_uuid = ''; // Initialize.

        do { // Avoid race issues with a loop.

            $attempts = $attempts ?? 0;
            ++$attempts; // Counter.

            if (($existing_namespaced_primary_key_uuid = (string) $this->Pool->get($namespaced_primary_key))) {
                $namespaced_primary_key_uuid = $existing_namespaced_primary_key_uuid;
                break; // All good; stop here.
            }
            if (!isset($new_namespaced_primary_key_uuid)) {
                $new_namespaced_primary_key_uuid = $this->a::uuidV4();
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
            throw new Exception(sprintf('Sub key too long; %1$s bytes max.', 250 - strlen($namespaced_primary_key_uuid_prefix)));
        }
        return $namespaced_key;
    }

    /**
     * Namespace primary key.
     *
     * @since 151216 Memcached utilities.
     *
     * @param string $primary_key Primary key.
     *
     * @return string Namespaced primary key.
     */
    protected function nspKey(string $primary_key): string
    {
        if (!$this->Pool) {
            return ''; // Not possible.
        }
        if (!isset($primary_key[0])) {
            return ''; // Not possible.
        }
        $namespace_prefix       = '¤'.$this->namespace.'\\';
        $namespaced_primary_key = $namespace_prefix.$primary_key;

        if (isset($namespaced_primary_key[251])) {
            throw new Exception(sprintf('Primary key too long; %1$s bytes max.', 250 - strlen($namespace_prefix)));
        }
        return $namespaced_primary_key;
    }

    /**
     * Servers differ? i.e., current vs. active.
     *
     * @since 151216 Memcached utilities.
     *
     * @return bool True if servers differ.
     */
    protected function serversDiffer(): bool
    {
        if (!$this->Pool) {
            return false; // Not possible.
        }
        $active_servers = []; // Initialize array.

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
     * @since 151216 Memcached utilities.
     */
    protected function maybeAddServerConnections()
    {
        if (!$this->Pool) {
            return; // Not possible.
        }
        if ($this->serversDiffer()) {
            $this->Pool->quit();
            $this->Pool->resetServerList();
            $this->Pool->addServers($this->servers);
        }
    }
    /*
    Memcached class constants in PHP v7.0.

    'LIBMEMCACHED_VERSION_HEX'             => 16777240
    'OPT_COMPRESSION'                      => -1001
    'OPT_COMPRESSION_TYPE'                 => -1004
    'OPT_PREFIX_KEY'                       => -1002
    'OPT_SERIALIZER'                       => -1003
    'OPT_STORE_RETRY_COUNT'                => -1005
    'HAVE_IGBINARY'                        => 0
    'HAVE_JSON'                            => 0
    'HAVE_MSGPACK'                         => 0
    'HAVE_SESSION'                         => 1
    'HAVE_SASL'                            => 1
    'OPT_HASH'                             => 2
    'HASH_DEFAULT'                         => 0
    'HASH_MD5'                             => 1
    'HASH_CRC'                             => 2
    'HASH_FNV1_64'                         => 3
    'HASH_FNV1A_64'                        => 4
    'HASH_FNV1_32'                         => 5
    'HASH_FNV1A_32'                        => 6
    'HASH_HSIEH'                           => 7
    'HASH_MURMUR'                          => 8
    'OPT_DISTRIBUTION'                     => 9
    'DISTRIBUTION_MODULA'                  => 0
    'DISTRIBUTION_CONSISTENT'              => 1
    'DISTRIBUTION_VIRTUAL_BUCKET'          => 6
    'OPT_LIBKETAMA_COMPATIBLE'             => 16
    'OPT_LIBKETAMA_HASH'                   => 17
    'OPT_TCP_KEEPALIVE'                    => 32
    'OPT_BUFFER_WRITES'                    => 10
    'OPT_BINARY_PROTOCOL'                  => 18
    'OPT_NO_BLOCK'                         => 0
    'OPT_TCP_NODELAY'                      => 1
    'OPT_SOCKET_SEND_SIZE'                 => 4
    'OPT_SOCKET_RECV_SIZE'                 => 5
    'OPT_CONNECT_TIMEOUT'                  => 14
    'OPT_RETRY_TIMEOUT'                    => 15
    'OPT_DEAD_TIMEOUT'                     => 36
    'OPT_SEND_TIMEOUT'                     => 19
    'OPT_RECV_TIMEOUT'                     => 20
    'OPT_POLL_TIMEOUT'                     => 8
    'OPT_CACHE_LOOKUPS'                    => 6
    'OPT_SERVER_FAILURE_LIMIT'             => 21
    'OPT_AUTO_EJECT_HOSTS'                 => 28
    'OPT_HASH_WITH_PREFIX_KEY'             => 25
    'OPT_NOREPLY'                          => 26
    'OPT_SORT_HOSTS'                       => 12
    'OPT_VERIFY_KEY'                       => 13
    'OPT_USE_UDP'                          => 27
    'OPT_NUMBER_OF_REPLICAS'               => 29
    'OPT_RANDOMIZE_REPLICA_READ'           => 30
    'OPT_REMOVE_FAILED_SERVERS'            => 35
    'OPT_SERVER_TIMEOUT_LIMIT'             => 37
    'RES_SUCCESS'                          => 0
    'RES_FAILURE'                          => 1
    'RES_HOST_LOOKUP_FAILURE'              => 2
    'RES_UNKNOWN_READ_FAILURE'             => 7
    'RES_PROTOCOL_ERROR'                   => 8
    'RES_CLIENT_ERROR'                     => 9
    'RES_SERVER_ERROR'                     => 10
    'RES_WRITE_FAILURE'                    => 5
    'RES_DATA_EXISTS'                      => 12
    'RES_NOTSTORED'                        => 14
    'RES_NOTFOUND'                         => 16
    'RES_PARTIAL_READ'                     => 18
    'RES_SOME_ERRORS'                      => 19
    'RES_NO_SERVERS'                       => 20
    'RES_END'                              => 21
    'RES_ERRNO'                            => 26
    'RES_BUFFERED'                         => 32
    'RES_TIMEOUT'                          => 31
    'RES_BAD_KEY_PROVIDED'                 => 33
    'RES_STORED'                           => 15
    'RES_DELETED'                          => 22
    'RES_STAT'                             => 24
    'RES_ITEM'                             => 25
    'RES_NOT_SUPPORTED'                    => 28
    'RES_FETCH_NOTFINISHED'                => 30
    'RES_SERVER_MARKED_DEAD'               => 35
    'RES_UNKNOWN_STAT_KEY'                 => 36
    'RES_INVALID_HOST_PROTOCOL'            => 34
    'RES_MEMORY_ALLOCATION_FAILURE'        => 17
    'RES_CONNECTION_SOCKET_CREATE_FAILURE' => 11
    'RES_E2BIG'                            => 37
    'RES_KEY_TOO_BIG'                      => 39
    'RES_SERVER_TEMPORARILY_DISABLED'      => 47
    'RES_SERVER_MEMORY_ALLOCATION_FAILURE' => 48
    'RES_AUTH_PROBLEM'                     => 40
    'RES_AUTH_FAILURE'                     => 41
    'RES_AUTH_CONTINUE'                    => 42
    'RES_PAYLOAD_FAILURE'                  => -1001
    'SERIALIZER_PHP'                       => 1
    'SERIALIZER_IGBINARY'                  => 2
    'SERIALIZER_JSON'                      => 3
    'SERIALIZER_JSON_ARRAY'                => 4
    'SERIALIZER_MSGPACK'                   => 5
    'COMPRESSION_FASTLZ'                   => 2
    'COMPRESSION_ZLIB'                     => 1
    'GET_PRESERVE_ORDER'                   => 1
    'GET_ERROR_RETURN_VALUE'               => false
    */
}
