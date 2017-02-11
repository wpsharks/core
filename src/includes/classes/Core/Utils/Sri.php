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
     * Cache directory.
     *
     * @since 170211.63148
     *
     * @type string
     */
    protected $cache_dir;

    /**
     * Class constructor.
     *
     * @since 170211.63148 SRI utils.
     *
     * @param Classes\App $App Instance.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        if (!$this->App->Config->©fs_paths['©cache_dir']) {
            throw $this->c::issue('Missing cache directory.');
        }
        $this->cache_dir = $this->App->Config->©fs_paths['©cache_dir'].'/sris';
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
        # The URL is empty or invalid?

        if (!($url = $this->c::mbTrim($url))) {
            return ''; // Not possible.
        }
        # Already cached this in memory?

        if (($sri = &$this->cacheKey(__FUNCTION__, $url)) !== null) {
            return $sri; // Cached this already.
        }
        # Determine sharded cache directory and file.

        $url_sha1              = sha1($url); // This hash is for the cache only.
        $cache_dir             = $this->cache_dir.'/'.$this->c::sha1ModShardId($url_sha1, true);
        $cache_dir_permissions = $this->App->Config->©fs_permissions['©transient_dirs'];
        $cache_file            = $cache_dir.'/'.$url_sha1;

        # If possible, use cached response.

        if (($sri = $this->c::memcacheGet('sris', $url_sha1))) {
            return $sri; // Ideal; avoids disk IO.
        } elseif (is_file($cache_file)) {
            return $sri = (string) file_get_contents($cache_file);
        }
        # Query for remote contents.

        $response = $this->c::remoteRequest('GET::'.$url, ['return_array' => true]);

        # Memory-cache only on error.

        if ($response['code'] !== 200) {
            return $sri = ''; // Memory only.
        }
        # Cache and return response.

        if (!is_dir($cache_dir)) {
            mkdir($cache_dir, $cache_dir_permissions, true);
        }
        $sri = 'sha256-'.base64_encode(hash('sha256', $response['body'], true));
        $this->c::memcacheSet('sris', $url_sha1, $sri);
        file_put_contents($cache_file, $sri);

        return $sri;
    }
}
