<?php
/**
 * ZenHub.
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
 * ZenHub.
 *
 * @since 170124.74961 GitHub utils.
 */
class ZenHub extends Classes\Core\Base\Core
{
    /**
     * Cache directory.
     *
     * @since 170124.74961
     *
     * @type string
     */
    protected $cache_dir;

    /**
     * Class constructor.
     *
     * @since 170124.74961 GitHub utils.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        if (!$this->App->Config->©fs_paths['©cache_dir']) {
            throw $this->c::issue('Missing cache directory.');
        }
        $this->cache_dir = $this->App->Config->©fs_paths['©cache_dir'].'/github';
    }

    /**
     * GET JSON response.
     *
     * @since 170124.74961 Initial release.
     *
     * @param string $url  API URL.
     * @param array  $args Additional args.
     *
     * @return \StdClass Including a boolean `success` property.
     */
    public function getJson(string $url, array $args = []): \StdClass
    {
        $default_args = [
            'cache'            => true,
            'cache_max_age'    => strtotime('-15 minutes'),
            'api_access_token' => $this->App->Config->©zenhub['©api_access_token'],
        ];
        $args += $default_args; // Merge defaults.

        $time = time(); // Current time.

        $args['cache']            = (bool) $args['cache'];
        $args['cache_max_age']    = max(0, min($time, (int) $args['cache_max_age']));
        $args['api_access_token'] = (string) $args['api_access_token'];

        $url_sha1              = sha1($url); // A SHA-1 hash of the URL.
        $cache_dir_permissions = $this->App->Config->©fs_permissions['©transient_dirs'];
        $cache_dir             = $this->cache_dir.'/'.$this->c::sha1ModShardId($url_sha1, true);
        $cache_file            = $cache_dir.'/'.$url_sha1; // Hash location.

        if ($args['cache'] && is_file($cache_file) && filemtime($cache_file) >= $args['cache_max_age']) {
            return $response_object = json_decode(file_get_contents($cache_file));
        }
        $response = $this->c::remoteRequest('GET::'.$url, [
            'headers' => [
                'accept: application/json',
                'x-authentication-token: '.$args['api_access_token'],
            ],
            'return_array' => true,
        ]); // Expecting the API to return JSON.

        $response_object = (object) [
            'success'  => $response['code'] && $response['code'] < 400,
            'data'     => json_decode($response['body']),
            'response' => $response,
        ];
        if ($args['cache']) {
            if (!is_dir($cache_dir)) {
                mkdir($cache_dir, $cache_dir_permissions, true);
            }
            file_put_contents($cache_file, json_encode($response_object));
        }
        return $response_object;
    }
}
