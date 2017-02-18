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
class Sri extends Classes\Core\Base\Core implements Interfaces\ByteConstants
{
    /**
     * Map.
     *
     * @since 170215.53419
     *
     * @type string
     */
    protected $map;

    /**
     * Map file.
     *
     * @since 170215.53419
     *
     * @type string
     */
    protected $map_file;

    /**
     * Map cache.
     *
     * @since 170215.53419
     *
     * @type string
     */
    protected $map_cache;

    /**
     * Map cache file.
     *
     * @since 170215.53419
     *
     * @type string
     */
    protected $map_cache_file;

    /**
     * Memcache enabled?
     *
     * @since 170215.53419
     *
     * @type bool
     */
    protected $memcache_enabled;

    /**
     * Cache max age.
     *
     * @since 170215.53419
     *
     * @type int Timestamp.
     */
    protected $cache_max_age;

    /**
     * Cache expires in.
     *
     * @since 170215.53419
     *
     * @type int Seconds.
     */
    protected $cache_expires_in;

    /**
     * Counter.
     *
     * @since 170215.53419
     *
     * @type int
     */
    protected static $content_checks;

    /**
     * Max checks.
     *
     * @since 170215.53419
     *
     * @type int
     */
    protected $max_content_checks;

    /**
     * Current scheme.
     *
     * @since 170215.53419
     *
     * @type string
     */
    protected $current_scheme;

    /**
     * Current host.
     *
     * @since 170215.53419
     *
     * @type string
     */
    protected $current_host;

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
            // NOTE: This is an instance-based configuration option.
            // However, checks are counted statically across all instances.
            // So while it's possible to increase the max for your instance, when doing so,
            // please remember the counter is based on all checks that occur in a single process.
            'max_content_checks' => 2,

            // Maximum age of any cache entry.
            'cache_max_age'      => strtotime('-30 days'),
        ];
        $args += $default_args; // Merge defaults.

        if (!$this->App->Config->©fs_paths['©sris_dir']) {
            throw $this->c::issue('Missing SRIs directory.');
        } elseif (!$this->App->Config->©fs_paths['©cache_dir']) {
            throw $this->c::issue('Missing cache directory.');
        }
        $this->map_file           = $this->App->Config->©fs_paths['©sris_dir'].'/sris.json';
        $this->map_cache_file     = $this->App->Config->©fs_paths['©cache_dir'].'/sris/map.json';
        $this->memcache_enabled   = $this->c::memcacheEnabled();

        $time                     = time(); // Current time.
        $this->cache_max_age      = max(0, min($time, (int) $args['cache_max_age']));
        $this->cache_expires_in   = $time - $this->cache_max_age;

        static::$content_checks   = static::$content_checks ?? 0;
        $this->max_content_checks = (int) $args['max_content_checks'];

        $is_cli                   = $this->c::isCli();
        $this->current_scheme     = $is_cli ? '' : $this->c::currentScheme();
        $this->current_host       = $is_cli ? '' : $this->c::currentHost();
    }

    /**
     * Acquire SRI hash.
     *
     * @since 170211.63148 SRI utils.
     *
     * @param string $url                URI to hash.
     * @param bool   $___is_parent_check Internal use only.
     *
     * @return string SRI hash, else empty string.
     */
    public function __invoke(string $url, bool $___is_parent_check = false): string
    {
        if (!$url) { // URL is empty?
            return ''; // Stop here.
        } elseif ($this->isLocal($url)) {
            return ''; // Unnecessary.
        }
        $sha1 = sha1($url); // Key for lookups.

        // ↑ NOTE: The sha is based on the URL given.
        // It's important not to alter the scheme here.
        // `https://` files may serve slightly different content.
        // e.g., A dynamic script that alters paths based on scheme.
        // However, when using a map, `//` can be used to cover all schemes.

        // ↓ NOTE: The way in which parent checks occur.
        // Each parent check will result in additional parent checks.
        // i.e., It runs all the way up the ladder recursively.

        // ↓ NOTE: Remember the in-process cache variables by &reference.
        // i.e., `&$sri` and `&$p_sri` are an in-process cache of all checks.

        $p = $this->App->Parent; // Possibly a parent application.
        $p = $p ? $p->Utils->©Sris : null; // Shorter reference.

        if (($sri = &$this->checkProcess($url, $sha1)) !== null) {
            return $sri; // Via in-process cache of the SRI.
        } elseif ($p && ($p_sri = &$p->checkProcess($url, $sha1)) !== null) {
            return $p_sri; // Via parent in-process cache.
        }
        if (($sri = $this->checkMemcache($url, $sha1)) !== null) {
            return $sri; // Via memcache; avoids disk IO.
        } elseif ($p && ($p_sri = $p->checkMemcache($url, $sha1)) !== null) {
            return $p_sri; // Via parent memcache.
        }
        if (($sri = $this->checkMap($url, $sha1)) !== null) {
            $this->memcacheIt($url, $sha1, $sri);
            return $sri; // Via one read of the map file.
        } elseif ($p && ($p_sri = $p->checkMap($url, $sha1)) !== null) {
            return $p_sri; // Via parent map file.
        }
        if (($sri = $this->checkMapCache($url, $sha1)) !== null) {
            $this->memcacheIt($url, $sha1, $sri);
            return $sri; // Via additional read of map cache file.
        } elseif ($p && ($p_sri = $p->checkMapCache($url, $sha1)) !== null) {
            return $p_sri; // Via parent map cache file.
        }
        if (($sri = $this->checkContents($url, $sha1)) !== null) {
            $this->cacheIt($url, $sha1, $sri);
            return $sri; // First-time SHA.
        }
        return $sri = ''; // Not possible at this time.
    }

    /**
     * Checks for SRI in process.
     *
     * @since 170211.63148 SRI utils.
     *
     * @param string $url  URL to get SRI for.
     * @param string $sha1 SHA-1 hash of URL.
     *
     * @return string|null SRI hash, else `null`.
     */
    public function &checkProcess(string $url, string $sha1)
    {
        if (is_string($sri = &$this->cacheGet('sris', $sha1))) {
            return $sri; // Cached this already.
        } else { // Set in-process cache to `null`.
            $sri = null; // Not in the cache.
            return $sri; // By reference.
        }
    }

    /**
     * Checks for SRI in memcache.
     *
     * @since 170211.63148 SRI utils.
     *
     * @param string $url  URL to get SRI for.
     * @param string $sha1 SHA-1 hash of URL.
     *
     * @return string|null SRI hash, else `null`.
     */
    public function checkMemcache(string $url, string $sha1)
    {
        if (!$this->memcache_enabled) {
            return $sri = null; // Not possible.
        } elseif (is_string($sri = $this->c::memcacheGet('sris', $sha1))) {
            return $sri; // Ideal; this avoids disk IO.
        }
        return $sri = null; // Not in the cache.
    }

    /**
     * Checks for SRI in map.
     *
     * @since 170211.63148 SRI utils.
     *
     * @param string $url  URL to get SRI for.
     * @param string $sha1 SHA-1 hash of URL.
     *
     * @return string|null SRI hash, else `null`.
     */
    public function checkMap(string $url, string $sha1)
    {
        $this->map = $this->getMap();

        if (isset($this->map[$url]['sri'])) {
            return $sri = (string) $this->map[$url]['sri'];
        }
        if (mb_strpos($url, '//') !== 0) { // Should check `//`?
            ${'//'} = preg_replace('/^https?\:/ui', '', $url);

            if (isset($this->map[${'//'}]['sri'])) {
                return $sri = (string) $this->map[${'//'}]['sri'];
            } // i.e., If a map entry covers all schemes.
        }
        return $sri = null; // Not in the map.
    }

    /**
     * Checks for SRI in map cache.
     *
     * @since 170211.63148 SRI utils.
     *
     * @param string $url  URL to get SRI for.
     * @param string $sha1 SHA-1 hash of URL.
     *
     * @return string|null SRI hash, else `null`.
     */
    public function checkMapCache(string $url, string $sha1)
    {
        $this->map_cache = $this->getMapCache();

        if (isset($this->map_cache[$url]['sri']) && $this->map_cache[$url]['time'] >= $this->cache_max_age) {
            return $sri = (string) $this->map_cache[$url]['sri'];
        }
        if (mb_strpos($url, '//') !== 0) { // Should check `//`?
            ${'//'} = preg_replace('/^https?\:/ui', '', $url);

            if (isset($this->map_cache[${'//'}]['sri']) && $this->map_cache[${'//'}]['time'] >= $this->cache_max_age) {
                return $sri = (string) $this->map_cache[${'//'}]['sri'];
            } // i.e., If a map entry covers all schemes.
        }
        return $sri = null; // Not in the map.
    }

    /**
     * Check contents.
     *
     * @since 170211.63148 SRI utils.
     *
     * @param string $url  URL to get SRI for.
     * @param string $sha1 SHA-1 hash of URL.
     *
     * @return string|null SRI hash, else `null`.
     */
    protected function checkContents(string $url, string $sha1)
    {
        if (static::$content_checks >= $this->max_content_checks) {
            return $sri = null; // Not possible at this time.
        } // There is a limit on the number of checks per process.

        if (mb_stripos($url, '//') === 0) {
            $url = ($this->current_scheme ?: 'http').':'.$url;
        } // Because we must have a scheme to do a lookup.

        $args = [ // HTTP args.
            'max_con_secs'    => 2,
            'max_stream_secs' => 6,
            'max_stream_size' => $this::BYTES_IN_MB * 2,
        ]; // Most servers can download 1MB+ in this time.

        ++static::$content_checks; // Increment counter.
        $response = $this->c::remoteResponse('GET::'.$url, $args);

        if ($response->code !== 200) {
            return $sri = ''; // Unable to determine.
            // However, we do return an empty SRI in this case anyway.

            // NOTE: This may (at times) return an empty SRI whenever
            // there is a temporary connectivity issue that is resolved later.

            // Rationale: If we chose not to cache failed responses, that could result in HTTP
            // requests occurring over and over again, which is want we need to avoid at all costs.
            // Therefore, if it fails, game is over until clearing the cache or adding a new map entry.
        }
        return $sri = 'sha256-'.base64_encode(hash('sha256', $response->body, true));
    }

    /**
     * Cache the SRI hash.
     *
     * @since 170211.63148 SRI utils.
     *
     * @param string $url  URL to get SRI for.
     * @param string $sha1 SHA-1 hash of URL.
     * @param string $sri  The SRI hash.
     *
     * @note The `$sri` is allowed to be empty.
     */
    protected function cacheIt(string $url, string $sha1, string $sri)
    {
        $this->memcacheIt($url, $sha1, $sri);
        $this->mapCacheIt($url, $sha1, $sri);
    }

    /**
     * Memcache the SRI hash.
     *
     * @since 170211.63148 SRI utils.
     *
     * @param string $url  URL to get SRI for.
     * @param string $sha1 SHA-1 hash of URL.
     * @param string $sri  The SRI hash.
     *
     * @note The `$sri` is allowed to be empty.
     */
    protected function memcacheIt(string $url, string $sha1, string $sri)
    {
        if ($this->memcache_enabled) {
            $this->c::memcacheSet('sris', $sha1, $sri, $this->cache_expires_in);
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
     *
     * @note The `$sri` is allowed to be empty.
     */
    protected function mapCacheIt(string $url, string $sha1, string $sri)
    {
        $this->map_cache       = $this->getMapCache();
        $this->map_cache[$url] = ['time' => time(), 'sri' => $sri];

        if (mb_strpos($url, '//') === 0) {
            unset($this->map_cache['http:'.$url]);
            unset($this->map_cache['https:'.$url]);
        } // Don't need all schemes, `//` will suffice.

        foreach ($this->map_cache as $_url => $_entry) {
            if ($_entry['time'] < $this->cache_max_age) {
                unset($this->map_cache[$_url]);
            } // Purges stale cache entries.
        } // unset($_url, $_entry); // Housekeeping.

        $map_cache_dir   = dirname($this->map_cache_file);
        $transient_perms = $this->App->Config->©fs_permissions['©transient_dirs'];

        if (!is_dir($map_cache_dir)) {
            mkdir($map_cache_dir, $transient_perms, true);
        }
        file_put_contents($this->map_cache_file, json_encode($this->map_cache, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    /**
     * Get the SRIs map.
     *
     * @since 170215.53419 SRI utils.
     *
     * @return array Map of SRIs.
     */
    protected function &getMap(): array
    {
        if (!isset($this->map)) {
            $this->map = []; // Define.

            if (is_file($this->map_file)) {
                $this->map = file_get_contents($this->map_file);
                $this->map = json_decode($this->map, true);
                $this->map = is_array($this->map) ? $this->map : [];
            } // Only if file exists, otherwise empty array.
        }
        return $this->map;
    }

    /**
     * Get SRIs map cache.
     *
     * @since 170215.53419 SRI utils.
     *
     * @return array SRIs map cache.
     */
    protected function &getMapCache(): array
    {
        if (!isset($this->map_cache)) {
            $this->map_cache = []; // Define.

            if (is_file($this->map_cache_file)) {
                $this->map_cache = file_get_contents($this->map_cache_file);
                $this->map_cache = json_decode($this->map_cache, true);
                $this->map_cache = is_array($this->map_cache) ? $this->map_cache : [];
            } // Only if file exists, otherwise empty array.
        }
        return $this->map_cache;
    }

    /**
     * URL is local?
     *
     * @since 170215.53419 SRI utils.
     *
     * @param string $url URL to get SRI for.
     *
     * @return bool|null True if URL is local.
     */
    protected function isLocal(string $url)
    {
        if (mb_stripos($url, '//') === false) {
            return true; // Relative.
        } elseif ($this->current_host) {
            return mb_stripos($url, '//'.$this->current_host.'/') !== false;
        }
        return null; // Unable to determine.
    }
}
