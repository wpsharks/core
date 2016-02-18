<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Functions\__;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * WebPurify.
 *
 * @since 15xxxx Badwords checker.
 */
class WebPurify extends Classes\AppBase
{
    /**
     * Cache directory.
     *
     * @since 15xxxx
     *
     * @type string
     */
    protected $cache_dir;

    /**
     * Class constructor.
     *
     * @since 15xxxx Badwords checker.
     */
    public function __construct()
    {
        parent::__construct();

        if (!$this->App->Config->fs_paths['cache_dir']) {
            throw new Exception('Missing cache directory.');
        } elseif (!$this->App->Config->webpurify['api_key']) {
            throw new Exception('Missing WebPurify API key.');
        }
        $this->cache_dir = $this->App->Config->fs_paths['cache_dir'].'/webpurify';
    }

    /**
     * Check a slug for bad words.
     *
     * @since 15xxxx Badwords checker.
     *
     * @return bool True if slug contains bad words.
     */
    public function checkSlug(string $slug): bool
    {
        $text = mb_strtolower($slug);
        $text = preg_replace('/[^\p{L}\p{N}]/ui', ' ', $text);
        $text = preg_replace('/\s+/u', ' ', $text);

        return $this->check($text);
    }

    /**
     * Check a string for bad words.
     *
     * @since 15xxxx Badwords checker.
     *
     * @param string $text Input text to check.
     *
     * @return bool True if string contains bad words.
     *
     * @see https://www.webpurify.com/documentation/methods/check/
     */
    public function check(string $text): bool
    {
        # The text is empty?

        if (!($text = c\mb_trim($text))) {
            return false; // Nothing to do.
        }
        # Already cached this in memory?

        if (!is_null($check = &$this->cacheKey(__FUNCTION__, $text))) {
            return $check; // Cached this already.
        }
        # Prepare endpoint/request args.

        $endpoint_args = [
            'method'  => 'webpurify.live.check',
            'api_key' => $this->App->Config->webpurify['api_key'],
            'text'    => $text,
            'format'  => 'json',
            'lang'    => 'en',
            'rsp'     => '0',
        ];
        $request_args = [
            'max_con_secs'    => 3,
            'max_stream_secs' => 3,
            'return_array'    => true,
        ];
        $endpoint = 'http://api1.webpurify.com/services/rest/';
        $endpoint = c\add_url_query_args($endpoint_args, $endpoint);

        # Determine sharded cache directory and file.

        $endpoint_sha1         = sha1($endpoint);
        $cache_dir             = $this->cache_dir.'/'.c\sha1_mod_shard_id($endpoint_sha1, true);
        $cache_dir_permissions = $this->App->Config->fs_permissions['transient_dirs'];
        $cache_file            = $cache_dir.'/'.$endpoint_sha1;

        # Use cached response if at all possible.

        if (is_file($cache_file)) { // File exists?
            return $check = (bool) file_get_contents($cache_file);
        }
        # Query for remote response via WebPurify API endpoint.

        $response = c\remote_request($endpoint, $request_args);

        # Validate response; false (and no cache) on any error.

        if ($response['code'] === 104) {
            return false; // Do not cache.
        }
        if (!is_object($json = json_decode($response['body']))) {
            return false; // Do not cache.
        }
        if (!isset($json->rsp->{'@attributes'}->stat)
            || $json->rsp->{'@attributes'}->stat !== 'ok') {
            return false; // Do not cache.
        }
        if (!isset($json->rsp->found)) {
            return false; // Do not cache.
        }
        # Cache and return response.

        if (!is_dir($cache_dir)) {
            mkdir($cache_dir, $cache_dir_permissions, true);
        }
        $check = (bool) $json->rsp->found; // `> 0` = `true`.
        file_put_contents($cache_file, (string) (int) $check);

        return $check; // True or false.
    }
}
