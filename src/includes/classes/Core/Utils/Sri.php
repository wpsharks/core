<?php
/**
 * SRI utils.
 *
 * @author @jaswsinc
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
 * SRI utils.
 *
 * @since 170211.63148 SRI utils.
 */
class Sri extends Classes\Core\Base\Core
{
    /**
     * Map.
     *
     * @since 17xxxx
     *
     * @type string
     */
    protected $map;

    /**
     * Map file.
     *
     * @since 17xxxx
     *
     * @type string
     */
    protected $map_file;

    /**
     * Cache directory.
     *
     * @since 170211.63148
     *
     * @type string
     */
    protected $cache_dir;

    /**
     * Memcache enabled?
     *
     * @since 17xxxx
     *
     * @type bool
     */
    protected $memcache_enabled;

    /**
     * Counter.
     *
     * @since 17xxxx
     *
     * @type int
     */
    protected $content_checks;

    /**
     * Max checks.
     *
     * @since 17xxxx
     *
     * @type int
     */
    protected $max_content_checks;

    /**
     * Class constructor.
     *
     * @since 170211.63148 SRI utils.
     *
     * @param Classes\App $App  Instance.
     * @param array       $args Constructor args.
     */
    public function __construct(Classes\App $App, array $args = [])
    {
        parent::__construct($App);

        $default_args = [
            'max_content_checks' => 2,
        ];
        $args += $default_args; // Merge defaults.

        if (!$this->App->Config->©fs_paths['©sris_dir']) {
            throw $this->c::issue('Missing SRIs directory.');
        } elseif (!$this->App->Config->©fs_paths['©cache_dir']) {
            throw $this->c::issue('Missing cache directory.');
        }
        $this->map              = null; // Initialize JIT via `__invoke()`.
        $this->map_file         = $this->App->Config->©fs_paths['©sris_dir'].'/sris.json';
        $this->cache_dir        = $this->App->Config->©fs_paths['©cache_dir'].'/sris';
        $this->memcache_enabled = $this->c::memcacheEnabled();

        $this->content_checks     = 0; // Initialize.
        $this->max_content_checks = (int) $args['max_content_checks'];
    }

    /**
     * Acquire SRI hash.
     *
     * @since 170211.63148 SRI utils.
     *
     * @param string $url URI to hash.
     *
     * @return string SRI hash, else empty string.
     */
    public function __invoke(string $url): string
    {
        if (!$url) { // URL is empty?
            return ''; // Stop here.
        }
        $sha1 = sha1($url); // Key for lookups.

        if (($sri = &$this->checkProcess($url, $sha1))) {
            return $sri; // Avoids disk IO.
        } elseif (($sri = $this->checkMemcache($url, $sha1))) {
            return $sri; // Avoids disk IO.
        } elseif (($sri = $this->checkMap($url, $sha1))) {
            $this->memcacheIt($url, $sha1, $sri);
            return $sri; // Better than cache.
        } elseif (($sri = $this->checkCache($url, $sha1))) {
            $this->memcacheIt($url, $sha1, $sri);
            return $sri; // Slightly slower.
        } elseif ($this->content_checks < $this->max_content_checks) {
            $sri = $this->checkContents($url, $sha1);
            $this->cacheIt($url, $sha1, $sri);
            return $sri; // Cached now.
        }
        return ''; // Not possible at this time.
    }

    /**
     * Checks for SRI in process.
     *
     * @since 170211.63148 SRI utils.
     *
     * @param string $url  URL to get SRI for.
     * @param string $sha1 SHA-1 hash of URL.
     *
     * @return string SRI hash, if exists.
     */
    protected function &checkProcess(string $url, string $sha1): string
    {
        if (($sri = &$this->cacheGet('sris', $sha1))) {
            return $sri; // Cached this already.
        }
        $sri = ''; // Not in the cache.
        return $sri; // By reference.
    }

    /**
     * Checks for SRI in memcache.
     *
     * @since 170211.63148 SRI utils.
     *
     * @param string $url  URL to get SRI for.
     * @param string $sha1 SHA-1 hash of URL.
     *
     * @return string SRI hash, if exists.
     */
    protected function checkMemcache(string $url, string $sha1): string
    {
        if (!$this->memcache_enabled) {
            return ''; // Not possible.
        } elseif (($sri = $this->c::memcacheGet('sris', $sha1))) {
            return $sri; // Ideal; this avoids disk IO.
        }
        return $sri = ''; // Not in the cache.
    }

    /**
     * Checks for SRI in map.
     *
     * @since 170211.63148 SRI utils.
     *
     * @param string $url  URL to get SRI for.
     * @param string $sha1 SHA-1 hash of URL.
     *
     * @return string SRI hash, if exists.
     */
    protected function checkMap(string $url, string $sha1): string
    {
        if (!isset($this->map) && is_file($this->map_file)) {
            $this->map = json_decode(file_get_contents($this->map_file), true);
            $this->map = is_array($this->map) ? $this->map : [];
        } // JIT loading of the map; i.e., only when necessary.

        if ($this->map && isset($this->map[$url])) {
            return $sri = $this->map[$url];
        }
        return $sri = ''; // Not in the map.
    }

    /**
     * Checks for SRI in cache.
     *
     * @since 170211.63148 SRI utils.
     *
     * @param string $url  URL to get SRI for.
     * @param string $sha1 SHA-1 hash of URL.
     *
     * @return string SRI hash, if exists.
     */
    protected function checkCache(string $url, string $sha1)
    {
        $shard_id   = $this->c::sha1ModShardId($sha1, true);
        $cache_dir  = $this->cache_dir.'/'.$shard_id;
        $cache_file = $cache_dir.'/'.$sha1;

        if (is_file($cache_file)) {
            return $sri = (string) file_get_contents($cache_file);
        }
        return $sri = ''; // Not in the cache.
    }

    /**
     * Check contents.
     *
     * @since 170211.63148 SRI utils.
     *
     * @param string $url  URL to get SRI for.
     * @param string $sha1 SHA-1 hash of URL.
     *
     * @return string SRI hash from file contents.
     */
    protected function checkContents(string $url, string $sha1): string
    {
        if ($this->content_checks >= $this->max_content_checks) {
            return $sri = ''; // Not possible at this time.
        } // There is a limit on the number of checks per process.

        ++$this->content_checks; // Increment counter.
        $contents   = $this->c::remoteRequest('GET::'.$url);
        return $sri = 'sha256-'.base64_encode(hash('sha256', $contents, true));
    }

    /**
     * Cache the SRI hash.
     *
     * @since 170211.63148 SRI utils.
     *
     * @param string $url  URL to get SRI for.
     * @param string $sha1 SHA-1 hash of URL.
     * @param string $sri  The SRI hash.
     */
    protected function cacheIt(string $url, string $sha1, string $sri)
    {
        $this->memcacheIt($url, $sha1, $sri);
        $this->mapCacheIt($url, $sha1, $sri);
        $this->fileCacheIt($url, $sha1, $sri);
    }

    /**
     * Memcache the SRI hash.
     *
     * @since 170211.63148 SRI utils.
     *
     * @param string $url  URL to get SRI for.
     * @param string $sha1 SHA-1 hash of URL.
     * @param string $sri  The SRI hash.
     */
    protected function memcacheIt(string $url, string $sha1, string $sri)
    {
        if ($this->memcache_enabled) {
            $this->c::memcacheSet('sris', $sha1, $sri);
        } // This is, by far, the fastest way.
    }

    /**
     * Map-cache the SRI hash.
     *
     * @since 170211.63148 SRI utils.
     *
     * @param string $url  URL to get SRI for.
     * @param string $sha1 SHA-1 hash of URL.
     * @param string $sri  The SRI hash.
     */
    protected function mapCacheIt(string $url, string $sha1, string $sri)
    {
        if (!isset($this->map) && is_file($this->map_file)) {
            $this->map = json_decode(file_get_contents($this->map_file), true);
            $this->map = is_array($this->map) ? $this->map : [];
        } // JIT loading of the map; i.e., only when necessary.

        $map_dir         = dirname($this->map_file);
        $transient_perms = $this->App->Config->©fs_permissions['©transient_dirs'];

        // Suppress write errors when building a map.
        // The `sris` directory may or may not be writable.
        // i.e., It is usually the `src/client-s` directory.

        if (!is_dir($map_dir)) {
            @mkdir($map_dir, $transient_perms, true);
        }
        $this->map[$url] = $sri; // Add to the map.
        @file_put_contents($this->map_file, json_encode($this->map, JSON_PRETTY_PRINT));
    }

    /**
     * Cache the SRI hash.
     *
     * @since 170211.63148 SRI utils.
     *
     * @param string $url  URL to get SRI for.
     * @param string $sha1 SHA-1 hash of URL.
     * @param string $sri  The SRI hash.
     */
    protected function fileCacheIt(string $url, string $sha1, string $sri)
    {
        $shard_id        = $this->c::sha1ModShardId($sha1, true);
        $cache_dir       = $this->cache_dir.'/'.$shard_id;
        $cache_file      = $cache_dir.'/'.$sha1;
        $transient_perms = $this->App->Config->©fs_permissions['©transient_dirs'];

        if (!is_dir($cache_dir)) {
            mkdir($cache_dir, $transient_perms, true);
        }
        file_put_contents($cache_file, $sri);
    }
}
