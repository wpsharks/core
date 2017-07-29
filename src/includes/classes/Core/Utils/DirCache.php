<?php
/**
 * Dir cache utils.
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
 * Dir cache utils.
 *
 * @since 17xxxx Dir cache utils.
 */
class DirCache extends Classes\Core\Base\Core
{
    /**
     * Directory.
     *
     * @since 17xxxx
     *
     * @type string
     */
    protected $dir;

    /**
     * Directory.
     *
     * @since 17xxxx
     *
     * @type string
     */
    protected $tmp_dir;

    /**
     * Class constructor.
     *
     * @since 17xxxx Dir cache utils.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        if (!$this->App->Config->©fs_paths['©cache_dir']) {
            throw $this->c::issue('Missing cache directory.');
        }
        $this->dir     = $this->App->Config->©fs_paths['©cache_dir'];
        $this->tmp_dir = $this->c::tmpDir(); // App-specific.
    }

    /**
     * Get a cached value.
     *
     * @since 17xxxx Dir cache utils.
     *
     * @param string     $primary_key Primary key.
     * @param string|int $sub_key     Sub-key to get.
     * @param int        $max_age     Max age (in seconds).
     * @param array      $args        Any behavioral args.
     *
     * @return mixed|null Null if missing or expired.
     */
    public function get(string $primary_key, $sub_key, int $max_age = 0, array $args = [])
    {
        if (!isset($primary_key, $sub_key)) {
            return null; // Not possible.
        }
        $file = $this->dir; // App-specific.
        $file .= '/'.$this->subPath($primary_key, $sub_key, $args);

        if (!is_file($file)) {
            return null; // Cache miss.
        } elseif ($max_age && time() - filemtime($file) > $max_age) {
            return null; // Cache miss, too old.
        } elseif (($cache = file_get_contents($file)) === false) {
            return null; // Cache miss, read failure.
        } elseif (($cache = unserialize($cache)) === false) {
            return null; // Cache miss, unserialize failure.
        }
        if (isset($cache['x___value'], $cache['x___expires'])) {
            if ($cache['x___expires'] && $cache['x___expires'] <= time()) {
                return null; // Cache miss, value has expired.
            } else {
                return $cache['x___value']; // Cache hit!
            }
        } else { // e.g., Perhaps stored by an older version.
            return null; // Cache miss, unexpected data.
        }
    }

    /**
     * Cache a value.
     *
     * @since 17xxxx Dir cache utils.
     *
     * @param string     $primary_key   Primary key.
     * @param string|int $sub_key       Sub-key to set.
     * @param mixed      $value         The value to cache.
     * @param int        $expires_after Expires after (in seconds).
     * @param array      $args          Any behavioral args.
     *
     * @NOTE Not possible to cache a `null` value because
     * {@link get()} returns `null` to indicate a cache miss.
     */
    public function set(string $primary_key, $sub_key, $value, int $expires_after = 0, array $args = [])
    {
        if (!isset($primary_key, $sub_key, $value)) {
            return; // Not possible.
        }
        $file = $this->dir; // App-specific.
        $file .= '/'.$this->subPath($primary_key, $sub_key, $args);
        $tmp_file = $this->tmp_dir.'/'.$this->c::uniqueId();

        $dir             = dirname($file); // May need to create this.
        $tmp_dir         = dirname($tmp_file); // May need to create this.
        $dir_permissions = $this->App->Config->©fs_permissions['©transient_dirs'];

        if ($expires_after > 0) {
            $expires = time() + $expires_after;
        } else {
            $expires = 0; // i.e., no expiration time.
        }
        $cache = serialize([
            'x___value'   => $value,
            'x___expires' => $expires,
        ]);
        if (!is_dir($dir)) {
            mkdir($dir, $dir_permissions, true);
        }
        if (!is_dir($tmp_dir)) {
            mkdir($tmp_dir, $dir_permissions, true);
        }
        if (file_put_contents($tmp_file, $cache) === false) {
            @unlink($tmp_file); // Cleanup on error.
            throw $this->c::issue(vars(), 'Cache `$tmp_file` write failure.');
            //
        } elseif (!rename($tmp_file, $file)) {
            @unlink($tmp_file); // Cleanup on error.
            throw $this->c::issue(vars(), 'Cache `$file` rename failure.');
        }
    }

    /**
     * Clear cache value(s).
     *
     * @since 17xxxx Dir cache utils.
     *
     * @param string          $primary_key Primary key.
     * @param string|int|null $sub_key     Sub-key (optional).
     * @param array           $args        Any behavioral args.
     *
     * @return bool True on success.
     */
    public function clear(string $primary_key, $sub_key = null, array $args = []): bool
    {
        if (!isset($sub_key)) {
            $dir = $this->dir; // App-specific.
            $dir .= '/'.$this->subPath($primary_key, null, $args);
            return $this->c::deleteDir($dir, true);
        } else {
            $file = $this->dir; // App-specific.
            $file .= '/'.$this->subPath($primary_key, $sub_key, $args);
            return @unlink($file);
        }
    }

    /**
     * Key sub-path.
     *
     * @since 17xxxx Dir cache utils.
     *
     * @param string          $primary_key Primary key.
     * @param string|int|null $sub_key     Sub-key to get.
     * @param array           $args        Any behavioral args.
     *
     * @return string Key sub-path.
     */
    protected function subPath(string $primary_key, $sub_key = null, array $args = []): string
    {
        if (!isset($sub_key)) {
            if (empty($args['preserve_keys'])) {
                extract($this->sha1Keys($primary_key, null, $args));
            }
            return $sub_path = $primary_key;
        } else {
            if (empty($args['preserve_keys'])) {
                extract($this->sha1Keys($primary_key, $sub_key, $args));
            }
            $shard_id        = $this->c::sha1ModShardId($primary_key.$sub_key);
            return $sub_path = $primary_key.'/'.$shard_id.'/'.$sub_key;
        }
    }

    /**
     * SHA-1 cache keys.
     *
     * @since 17xxxx Dir cache utils.
     *
     * @param string          $primary_key Primary key.
     * @param string|int|null $sub_key     Sub-key to get.
     * @param array           $args        Any behavioral args.
     *
     * @return array `[primary_key]` or `[primary_key, sub_key]`.
     */
    protected function sha1Keys(string $primary_key, $sub_key = null, array $args = []): array
    {
        if (!$primary_key || !$this->c::isSha1($primary_key)) {
            $primary_key = sha1($primary_key);
        }
        if (!isset($sub_key)) {
            return compact('primary_key');
        } else {
            if (!$sub_key || !$this->c::isSha1($sub_key)) {
                $sub_key = sha1((string) $sub_key);
            }
            return compact('primary_key', 'sub_key');
        }
    }
}
