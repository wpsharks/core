<?php
/**
 * Twitter.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
//
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
//
use function assert as debug;
use function get_defined_vars as vars;
//
use Abraham\TwitterOAuth\TwitterOAuth;

/**
 * Twitter.
 *
 * @since 17xxxx Twitter utils.
 */
class Twitter extends Classes\Core\Base\Core
{
    /**
     * Remotes.
     *
     * @since 17xxxx
     *
     * @type array
     */
    protected $remotes;

    /**
     * Default credentials.
     *
     * @since 17xxxx
     *
     * @type array
     */
    protected $default_credentials;

    /**
     * Cache directory.
     *
     * @since 17xxxx
     *
     * @type string
     */
    protected $cache_dir;

    /**
     * Class constructor.
     *
     * @since 17xxxx Twitter utils.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        if (!$this->App->Config->©fs_paths['©cache_dir']) {
            throw $this->c::issue('Missing cache directory.');
        }
        $this->remotes             = [];
        $this->default_credentials = [
            'consumer_key'        => $this->App->Config->©twitter['©api_consumer_key'],
            'consumer_secret'     => $this->App->Config->©twitter['©api_consumer_secret'],
            'access_token'        => $this->App->Config->©twitter['©api_access_token'],
            'access_token_secret' => $this->App->Config->©twitter['©api_access_token_secret'],
        ];
        $this->cache_dir = $this->App->Config->©fs_paths['©cache_dir'].'/twitter';
    }

    /**
     * Get remote connection.
     *
     * @since 17xxxx Twitter utils.
     *
     * @param array $args Credentials.
     *
     * @return TwitterOAuth Remote instance.
     */
    public function getRemote(array $args = []): TwitterOAuth
    {
        $default_args = $this->default_credentials;

        $args += $default_args;
        $args = array_map('strval', $args);
        $hash = $this->remoteHash($args);

        if (isset($this->remotes[$hash])) {
            return $this->remotes[$hash];
        }
        return $this->remotes[$hash] = new TwitterOAuth(
            $args['consumer_key'],
            $args['consumer_secret'],
            $args['access_token'],
            $args['access_token_secret']
        );
    }

    /**
     * GET JSON response.
     *
     * @since 17xxxx Twitter utils.
     *
     * @param string $url  API path.
     * @param array  $args Additional args.
     *
     * @return \StdClass Including a `success` property.
     */
    public function getJson(string $path, array $args = []): \StdClass
    {
        $default_args = $this->default_credentials + [
            'request'       => [],
            'cache'         => true,
            'cache_max_age' => strtotime('-15 minutes'),
        ];
        $args += $default_args; // Merge defaults.

        $time = time(); // Current time.

        $args['request']       = (array) ($args['request'] ?: []);
        $args['cache']         = (bool) $args['cache']; // Enable cache?
        $args['cache_max_age'] = max(0, min($time, (int) $args['cache_max_age']));

        $remote_hash = $this->remoteHash($args);
        $request_str = serialize($args['request']);

        $cache_sha1            = sha1($remote_hash.':'.$path.':'.$request_str);
        $cache_dir_permissions = $this->App->Config->©fs_permissions['©transient_dirs'];
        $cache_dir             = $this->cache_dir.'/'.$this->c::sha1ModShardId($cache_sha1, true);
        $cache_file            = $cache_dir.'/'.$cache_sha1; // Hash location.

        if ($args['cache'] && is_file($cache_file) && filemtime($cache_file) >= $args['cache_max_age']) {
            return $response_object = json_decode(file_get_contents($cache_file));
        }
        $remote        = $this->getRemote($args);
        $response      = $remote->get($path, $args['request']);
        $response_code = (int) $remote->getLastHttpCode();

        $response_object = (object) [
            'success' => $response_code && $response_code < 400,
            'data'    => $response,
            'remote'  => $remote,
        ];
        if ($args['cache']) {
            if (!is_dir($cache_dir)) {
                mkdir($cache_dir, $cache_dir_permissions, true);
            }
            file_put_contents($cache_file, json_encode($response_object));
        }
        return $response_object;
    }

    /**
     * Get remote hash.
     *
     * @since 17xxxx Twitter utils.
     *
     * @param array $args Credentials.
     *
     * @return string Remote hash.
     */
    protected function remoteHash(array $args = []): string
    {
        $default_args = $this->default_credentials;

        $args += $default_args;
        $args        = array_map('strval', $args);
        $args        = array_intersect_key($args, $default_args);
        return $hash = sha1(serialize($args));
    }
}
