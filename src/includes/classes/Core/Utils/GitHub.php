<?php
/**
 * GitHub.
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
 * GitHub.
 *
 * @since 16xxxx GitHub utils.
 */
class GitHub extends Classes\Core\Base\Core
{
    /**
     * Cache directory.
     *
     * @since 16xxxx
     *
     * @var string
     */
    protected $cache_dir;

    /**
     * Class constructor.
     *
     * @since 16xxxx GitHub utils.
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
     * GET raw response.
     *
     * @since 16xxxx Initial release.
     *
     * @param string $url  API URL.
     * @param array  $args Additional args.
     *
     * @return \StdClass Including a boolean `success` property.
     */
    public function getRaw(string $url, array $args = []): \StdClass
    {
        $default_args = [
            'cache'            => true,
            'cache_max_age'    => strtotime('-15 minutes'),
            'api_access_token' => $this->App->Config->©github['©api_access_token'],
        ];
        $args += $default_args; // Merge defaults.

        $args['cache']            = (bool) $args['cache'];
        $args['cache_max_age']    = (int) $args['cache_max_age'];
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
                'accept: application/vnd.github.v3.raw',
                'authorization: token '.$args['api_access_token'],
            ],
            'return_array' => true,
        ]); // Expecting the API to return a `200` status.

        $response_object = (object) [
            'success'  => $response['code'] === 200,
            'data'     => &$response['body'],
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

    /**
     * POST/GET JSON response.
     *
     * @since 16xxxx Initial release.
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
            'api_access_token' => $this->App->Config->©github['©api_access_token'],
        ];
        $args += $default_args; // Merge defaults.

        $args['cache']            = (bool) $args['cache'];
        $args['cache_max_age']    = (int) $args['cache_max_age'];
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
                'authorization: token '.$args['api_access_token'],
            ],
            'return_array' => true,
        ]); // Expecting the API to return a `200` status.

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

    /**
     * POST JSON data.
     *
     * @since 16xxxx Initial release.
     *
     * @param string $url  API URL.
     * @param mixed  $data Data to POST.
     * @param array  $args Additional args.
     *
     * @return \StdClass Including a boolean `success` property.
     */
    public function postJson(string $url, $data, array $args = []): \StdClass
    {
        if (!is_string($data)) {
            $data = json_encode($data);
        }
        $default_args = [
            'cache'            => false,
            'cache_max_age'    => strtotime('-15 minutes'),
            'api_access_token' => $this->App->Config->©github['©api_access_token'],
        ];
        $args += $default_args; // Merge defaults.

        $args['cache']            = (bool) $args['cache'];
        $args['cache_max_age']    = (int) $args['cache_max_age'];
        $args['api_access_token'] = (string) $args['api_access_token'];

        $url_sha1              = sha1($url); // A SHA-1 hash of the URL.
        $cache_dir_permissions = $this->App->Config->©fs_permissions['©transient_dirs'];
        $cache_dir             = $this->cache_dir.'/'.$this->c::sha1ModShardId($url_sha1, true);
        $cache_file            = $cache_dir.'/'.$url_sha1; // Hash location.

        if ($args['cache'] && is_file($cache_file) && filemtime($cache_file) >= $args['cache_max_age']) {
            return $response_object = json_decode(file_get_contents($cache_file));
        }
        $response = $this->c::remoteRequest('POST::'.$url, [
            'headers' => [
                'accept: application/json',
                'content-type: application/json',
                'authorization: token '.$args['api_access_token'],
            ],
            'body'         => $data,
            'return_array' => true,
        ]); // Expecting the API to return a `200` status.

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

    /**
     * Issues refs.
     *
     * @since 16xxxx GitHub utils.
     *
     * @param mixed $value   Input value.
     * @param array $args    Additional args.
     * @param array $___refs Internal use only.
     *
     * @return array All found issue refs.
     */
    public function issueRefs($value, array $args = [], array $___refs = []): array
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => $_value) {
                $___refs += $this->issueRefs($_value, $args, $___refs);
            } // unset($_key, $_value);
            return $___refs;
        }
        $markdown = (string) $value;

        if (!isset($markdown[0])) {
            return $markdown;
        }
        $default_args = [
            'current_owner' => '',
            'current_repo'  => '',
        ];
        $args += $default_args; // Merge defaults.

        $current_owner = (string) $args['current_owner'];
        $current_repo  = (string) $args['current_repo'];
        $have_currents = $current_owner && $current_repo;

        $current_owner_regex = '(?<owner>'.$this->c::escRegex($current_owner).')';
        $owner_regex         = '(?<owner>[a-z][a-z0-9_\-]*?[a-z0-9])';
        $repo_regex          = '(?<repo>[a-z][a-z0-9_\-]*?[a-z0-9])';
        $issue_regex         = '(?<issue>[0-9]+)';
        $comment_regex       = '(?<comment>#[^#\s]+)?';

        $pull_url_regex            = '/(?<=^|[\s;,(<])https?\:\/\/github\.com\/'.$owner_regex.'\/'.$repo_regex.'\/(?<is_pull>pull)\/'.$issue_regex.$comment_regex.'(?=$|[\s.!?;,)>])/ui';
        $issue_url_regex           = '/(?<=^|[\s;,(<])https?\:\/\/github\.com\/'.$owner_regex.'\/'.$repo_regex.'\/issues\/'.$issue_regex.$comment_regex.'(?=$|[\s.!?;,)>])/ui';
        $owner_repo_issue_regex    = '/(?<=^|[\s;,(])'.$owner_regex.'\/'.$repo_regex.'#'.$issue_regex.'(?=$|[\s.!?;,)])/ui';
        $current_owner_issue_regex = $have_currents ? '/(?<=^|[\s;,(])'.$current_owner_regex.'#'.$issue_regex.'(?=$|[\s.!?;,)])/ui' : '';
        $issue_number_regex        = $have_currents ? '/(?<=^|[\s;,(])#'.$issue_regex.'(?=$|[\s.!?;,)])/ui' : '';

        foreach ([$pull_url_regex, $issue_url_regex, $owner_repo_issue_regex, $current_owner_issue_regex, $issue_number_regex] as $_regex) {
            if (!$_regex) { // Skip if empty; e.g., currents not available.
                continue; // Not possible; regex is empty.
            }
            $markdown = preg_replace_callback($_regex, function ($m) use (&$___refs, $current_owner, $current_repo, $have_currents) {
                if (!$have_currents && (empty($m['owner']) || empty($m['repo']))) {
                    return ''; // Not possible.
                }
                $is_pull = isset($m['is_pull']);
                $owner = !empty($m['owner']) ? $m['owner'] : $current_owner;
                $repo = !empty($m['repo']) ? $m['repo'] : $current_repo;
                $number = $m['issue']; // Always have this.
                $comment = $m['comment'] ?? '';

                $___refs[($is_pull ? 'PR:' : '').$owner.'/'.$repo.'#'.$number.$comment] = [
                    'is_pull' => $is_pull,
                    'owner'   => $owner,
                    'repo'    => $repo,
                    'number'  => $number,
                    'comment' => $comment,
                ];
                return '';
            }, $markdown);
        } // unset($_regex); // Housekeeping.

        return $___refs;
    }

    /**
     * Markdown issue refs.
     *
     * @since 16xxxx GitHub utils.
     *
     * @param mixed $value Input value.
     * @param array $args  Additional args.
     *
     * @return string|array|object Markdown'd issue refs.
     */
    public function mdIssueRefs($value, array $args = [])
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->mdIssueRefs($_value, $args);
            } // unset($_key, $_value);
            return $value;
        }
        $markdown = (string) $value;

        if (!isset($markdown[0])) {
            return $markdown;
        }
        $default_args = [
            'current_owner' => '',
            'current_repo'  => '',
        ];
        $args += $default_args; // Merge defaults.

        $current_owner = (string) $args['current_owner'];
        $current_repo  = (string) $args['current_repo'];
        $have_currents = $current_owner && $current_repo;

        $current_owner_regex = '(?<owner>'.$this->c::escRegex($current_owner).')';
        $owner_regex         = '(?<owner>[a-z][a-z0-9_\-]*?[a-z0-9])';
        $repo_regex          = '(?<repo>[a-z][a-z0-9_\-]*?[a-z0-9])';
        $issue_regex         = '(?<issue>[0-9]+)';
        $comment_regex       = '(?<comment>#[^#\s]+)?';

        $pull_url_regex            = '/(?<=^|[\s;,<]|(?<!\])\()https?\:\/\/github\.com\/'.$owner_regex.'\/'.$repo_regex.'\/(?<is_pull>pull)\/'.$issue_regex.$comment_regex.'(?=$|[\s.!?;,)>])/ui';
        $issue_url_regex           = '/(?<=^|[\s;,<]|(?<!\])\()https?\:\/\/github\.com\/'.$owner_regex.'\/'.$repo_regex.'\/issues\/'.$issue_regex.$comment_regex.'(?=$|[\s.!?;,)>])/ui';
        $owner_repo_issue_regex    = '/(?<=^|[\s;,]|(?<!\])\()'.$owner_regex.'\/'.$repo_regex.'#'.$issue_regex.'(?=$|[\s.!?;,)])/ui';
        $current_owner_issue_regex = $have_currents ? '/(?<=^|[\s;,]|(?<!\])\()'.$current_owner_regex.'#'.$issue_regex.'(?=$|[\s.!?;,)])/ui' : '';
        $issue_number_regex        = $have_currents ? '/(?<=^|[\s;,]|(?<!\])\()#'.$issue_regex.'(?=$|[\s.!?;,)])/ui' : '';

        $Tokenizer = $this->c::tokenize($markdown, ['md-fences']);
        $markdown  = &$Tokenizer->getString(); // By reference.

        foreach ([$pull_url_regex, $issue_url_regex, $owner_repo_issue_regex, $current_owner_issue_regex, $issue_number_regex] as $_regex) {
            if (!$_regex) { // Skip if empty; e.g., currents not available.
                continue; // Not possible; regex is empty.
            }
            $markdown = preg_replace_callback($_regex, function ($m) use ($current_owner, $current_repo, $have_currents) {
                if (!$have_currents && (empty($m['owner']) || empty($m['repo']))) {
                    return $m[0]; // Not possible.
                }
                $is_pull = isset($m['is_pull']);
                $owner = !empty($m['owner']) ? $m['owner'] : $current_owner;
                $repo = !empty($m['repo']) ? $m['repo'] : $current_repo;
                $number = $m['issue']; // Always have this.
                $comment = $m['comment'] ?? '';

                $url = 'https://github.com/'.urlencode($owner).
                       '/'.urlencode($repo).'/'.($is_pull ? 'pull' : 'issues').
                       '/'.urlencode($number).$comment;

                if ($have_currents && $owner === $current_owner) {
                    if ($repo === $current_repo) {
                        return '['.$this->c::escHtmlChars('#'.$number.($comment ? ' (comment)' : '')).']('.$url.')';
                    } else {
                        return '['.$this->c::escHtmlChars($repo.'#'.$number.($comment ? ' (comment)' : '')).']('.$url.')';
                    }
                } else {
                    return '['.$this->c::escHtmlChars($owner.'/'.$repo.'#'.$number.($comment ? ' (comment)' : '')).']('.$url.')';
                }
            }, $markdown);
        } // unset($_regex); // Housekeeping.

        return $markdown = $Tokenizer->restoreGetString();
    }
}
