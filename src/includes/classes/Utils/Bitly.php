<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Bitly utilities.
 *
 * @since 160102 Adding bitly.
 */
class Bitly extends Classes\Core
{
    /**
     * Cache directory.
     *
     * @since 150424
     *
     * @type string
     */
    protected $cache_dir;

    /**
     * Class constructor.
     *
     * @since 160102 Adding bitly.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        if (!$this->App->Config->fs_paths['cache_dir']) {
            throw new Exception('Missing cache directory.');
        } elseif (!$this->App->Config->bitly['api_key']) {
            throw new Exception('Missing API key.');
        }
        $this->cache_dir = $this->App->Config->fs_paths['cache_dir'].'/bitly';
    }

    /**
     * Shorten a URL.
     *
     * @since 160102 Adding bitly.
     *
     * @param string $long_url Long URL.
     * @param array  $args     Any additional args.
     *
     * @return string Short URL.
     */
    public function shorten(string $long_url, array $args = []): string
    {
        # Parse incoming arguments.

        if (!$long_url) {
            return ''; // Nothing to do.
        }
        $default_args = [
            'access_token' => $this->App->Config->bitly['api_key'],
            'longUrl'      => $long_url,
        ];
        $args = array_merge($default_args, $args);

        # Already cached this in memory?

        if (!is_null($short_url = &$this->cacheKey(__FUNCTION__, $args))) {
            return $short_url; // Cached this already.
        }
        # Prepare endpoint/request args.

        $endpoint_args = $args;
        $request_args  = [
            'max_con_secs'    => 5,
            'max_stream_secs' => 5,
            'return_array'    => true,
        ];
        $endpoint = 'https://api-ssl.bitly.com/v3/shorten';
        $endpoint = c\add_url_query_args($endpoint_args, $endpoint);

        # Determine sharded cache directory and file.

        $endpoint_sha1         = sha1($endpoint);
        $cache_dir             = $this->cache_dir.'/'.c\sha1_mod_shard_id($endpoint_sha1, true);
        $cache_dir_permissions = $this->App->Config->fs_permissions['transient_dirs'];
        $cache_file            = $cache_dir.'/'.$endpoint_sha1;

        # Use cached response if at all possible.

        if (is_file($cache_file)) { // File exists?
            return $short_url = (string) file_get_contents($cache_file);
        }
        # Query for remote response via Bitly API endpoint.

        $response = c\remote_request('GET::'.$endpoint, $request_args);

        # Validate response; no cache on any error.

        if ($response['code'] !== 200) {
            return $long_url; // Do not cache.
        }
        if (!is_object($json = json_decode($response['body']))) {
            return $long_url; // Do not cache.
        }
        if (!isset($json->status_txt) || $json->status_txt !== 'OK') {
            return $long_url; // Do not cache.
        }
        if (!isset($json->data->url) || !is_string($json->data->url)) {
            return $long_url; // Do not cache.
        }
        # Cache and return response.

        if (!is_dir($cache_dir)) {
            mkdir($cache_dir, $cache_dir_permissions, true);
        }
        $short_url = $json->data->url;
        file_put_contents($cache_file, $short_url);

        return $short_url;
    }

    /**
     * Link history.
     *
     * @since 160115 Bitly history.
     *
     * @param int   $page     Page.
     * @param int   $per_page Max per page.
     * @param array $args     Any additional args.
     *
     * @return array Link history array.
     */
    public function linkHistory(int $page = 1, int $per_page = 50, array $args = []): array
    {
        # Parse incoming arguments.

        $page     = max(1, $page);
        $per_page = max(1, $per_page);
        $offset   = ($page - 1) * $per_page;

        $default_args = [
            'access_token' => $this->App->Config->bitly['api_key'],
            'offset'       => $offset,
            'limit'        => $per_page,
            'private'      => 'off',
        ];
        $args = array_merge($default_args, $args);

        # Already cached this in memory?

        if (!is_null($history = &$this->cacheKey(__FUNCTION__, $args))) {
            return $history; // Cached this already.
        }
        # Prepare endpoint/request args.

        $endpoint_args = $args;
        $request_args  = [
            'max_con_secs'    => 5,
            'max_stream_secs' => 5,
            'return_array'    => true,
        ];
        $endpoint = 'https://api-ssl.bitly.com/v3/user/link_history';
        $endpoint = c\add_url_query_args($endpoint_args, $endpoint);

        # Determine sharded cache directory and file.

        $endpoint_sha1         = sha1($endpoint);
        $cache_dir             = $this->cache_dir.'/'.c\sha1_mod_shard_id($endpoint_sha1, true);
        $cache_dir_permissions = $this->App->Config->fs_permissions['transient_dirs'];
        $cache_file            = $cache_dir.'/'.$endpoint_sha1;

        # Use cached response if at all possible.

        if (is_file($cache_file)) { // File exists?
            return $history = (array) unserialize(file_get_contents($cache_file));
        }
        # Query for remote response via Bitly API endpoint.

        $response = c\remote_request('GET::'.$endpoint, $request_args);

        # Validate response; no cache on any error.

        if ($response['code'] !== 200) {
            return []; // Do not cache.
        }
        if (!is_object($json = json_decode($response['body']))) {
            return []; // Do not cache.
        }
        if (!isset($json->status_txt) || $json->status_txt !== 'OK') {
            return []; // Do not cache.
        }
        if (!isset($json->data->link_history) || !is_array($json->data->link_history)) {
            return []; // Do not cache.
        }
        # Cache and return response.

        if (!is_dir($cache_dir)) {
            mkdir($cache_dir, $cache_dir_permissions, true);
        }
        $history = $json->data->link_history;
        file_put_contents($cache_file, serialize($history));

        return $history;
    }
}
