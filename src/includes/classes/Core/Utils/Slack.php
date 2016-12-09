<?php
/**
 * Slack.
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
 * Slack.
 *
 * @since 161009 Slack utils.
 */
class Slack extends Classes\Core\Base\Core
{
    /**
     * Cache directory.
     *
     * @since 161009
     *
     * @var string
     */
    protected $cache_dir;

    /**
     * Class constructor.
     *
     * @since 161009 Slack utils.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        if (!$this->App->Config->©fs_paths['©cache_dir']) {
            throw $this->c::issue('Missing cache directory.');
        }
        $this->cache_dir = $this->App->Config->©fs_paths['©cache_dir'].'/slack';
    }

    /**
     * Notify a Slack channel.
     *
     * @since 161009 Slack utils.
     *
     * @param array $args Notification args.
     *
     * @return bool True if notification sent successfully.
     */
    public function notify(array $args): bool
    {
        # Establish arguments.

        $default_args = [
            'channel' => '',

            'username' => '',
            'icon_url' => '',

            'mrkdwn'     => true,
            'link_names' => true,
            'text'       => '',

            'attachment_color' => '#e8e8e8',

            'attachment_pretext' => '',

            'attachment_author_name' => '',
            'attachment_author_link' => '',
            'attachment_author_icon' => '',

            'attachment_title'      => '',
            'attachment_title_link' => '',
            'attachment_text'       => '',

            'attachment_image_url' => '',
            'attachment_thumb_url' => '',

            'attachment_footer_icon' => '',
            'attachment_footer'      => '',

            'attachments' => [], // Custom set of attachments.

            'api_webhook_url' => $this->App->Config->©slack['©api_webhook_url'],
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        foreach ($args as $_key => &$_value) {
            if (in_array($_key, ['mrkdwn', 'link_names'], true)) {
                $_value = (bool) $_value;
            } elseif (in_array($_key, ['attachments'], true)) {
                $_value = (array) $_value;
            } else {
                $_value = (string) $_value;
            }
        } // unset($_key, $_value);

        # General information.

        $data = [
            'channel' => $args['channel'],

            'username' => $args['username'],
            'icon_url' => $args['icon_url'],

            'mrkdwn'     => $args['mrkdwn'],
            'link_names' => $args['link_names'],
            'text'       => $args['text'],
        ];
        # Attachment (if applicable).

        if ($args['attachments']) {
            $data['attachments'] = $args['attachments'];
            //
        } elseif ($args['attachment_color'] && (0
                || $args['attachment_pretext']
                || $args['attachment_author_name']
                || $args['attachment_title']
                || $args['attachment_text']
                || $args['attachment_image_url']
                || $args['attachment_thumb_url']
                || $args['attachment_footer']
            )
        ) { // Add attachment.
            $data['attachments'] = [
                [
                    'mrkdwn_in' => ['pretext', 'text'],
                    'color'     => $args['attachment_color'],

                    'pretext' => $args['attachment_pretext'],

                    'author_name' => $args['attachment_author_name'],
                    'author_link' => $args['attachment_author_link'],
                    'author_icon' => $args['attachment_author_icon'],

                    'title'      => $args['attachment_title'],
                    'title_link' => $args['attachment_title_link'],

                    'text' => $args['attachment_text'],

                    'image_url' => $args['attachment_image_url'],
                    'thumb_url' => $args['attachment_thumb_url'],

                    'footer_icon' => $args['attachment_footer_icon'],
                    'footer'      => $args['attachment_footer'],
                ],
            ];
        }
        # Validate & finalize data elements.

        if (!$data['channel'] || !$data['username'] || !$data['icon_url'] || !$args['api_webhook_url']
            || (!$data['text'] && empty($data['attachments']))) {
            return false; // Not possible.
        }
        # POST, validate, and collect API response.

        $request_args = [
            'headers' => [
                'content-type: application/json',
            ],
            'body'         => json_encode($data),
            'return_array' => true,
        ];
        $response = $this->c::remoteRequest('POST::'.$args['api_webhook_url'], $request_args);

        # Validate response.

        if ($response['code'] !== 200) {
            debug(0, $this->c::issue(vars(), 'Bad response code.'));
            return false;
        } elseif ($response['body'] !== 'ok') {
            debug(0, $this->c::issue(vars(), 'Response body not `ok`.'));
            return false;
        }
        # Return success.

        return true;
    }

    /**
     * Slack mrkdwn.
     *
     * @since 16xxxx Initial release.
     *
     * @param mixed $value Input value.
     * @param array $args  Additional args.
     *
     * @return string|array|object Mrkdwn'd.
     */
    public function mrkdwn($value, array $args = [])
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->mrkdwn($_value, $args);
            } // unset($_key, $_value);
            return $value;
        }
        $markdown = (string) $value;

        if (!isset($markdown[0])) {
            return $markdown;
        }
        $default_args = [
            'current_gfm_owner' => '',
            'current_gfm_repo'  => '',
            'is_gfm'            => false,
        ];
        $args += $default_args; // Merge defaults.

        $is_gfm            = (bool) $args['is_gfm'];
        $current_gfm_owner = (string) $args['current_gfm_owner'];
        $current_gfm_repo  = (string) $args['current_gfm_repo'];

        // GitHub flavored markdown?
        if ($is_gfm) { // If so, convert issue references.
            $markdown = $this->c::mdGitHubIssueRefs($markdown, [
                'current_owner' => $current_gfm_owner,
                'current_repo'  => $current_gfm_repo,
            ]); // Keeps MD formatting.
        } // Converts MD issue references.

        // Begin conversions.
        $mrkdwn = $markdown; // Converting.

        // Normalize breaks for `/m` mode below.
        $mrkdwn = $this->c::normalizeEols($mrkdwn);

        // Remove language specifier from MD fences.
        $mrkdwn = preg_replace('/^(\h*)```[a-z0-9_\-]+(\v)/uim', '$1```$2', $mrkdwn);

        // Tokenize; i.e., strip MD fences.
        $Tokenizer = $this->c::tokenize($mrkdwn, ['md-fences']);
        $mrkdwn    = &$Tokenizer->getString(); // By reference.

        // Convert GFM `*italic*` into Slack `_italic_`.
        $mrkdwn = preg_replace('/(?<=^|[\s;,(])\*{1}([^*\v]+)\*{1}(?=$|[\s.!?;,)])/ui', '_$1_', $mrkdwn);

        // Convert GFM `**bold**` into Slack `*bold*`.
        $mrkdwn = preg_replace('/(?<=^|[\s;,(])\*{2}([^*\v]+)\*{2}(?=$|[\s.!?;,)])/ui', '*$1*', $mrkdwn);

        // Convert GFM `~~strike~~` into Slack `~strike~`.
        $mrkdwn = preg_replace('/(?<=^|[\s;,(])~{2}([^~\v]+)~{2}(?=$|[\s.!?;,)])/ui', '~$1~', $mrkdwn);

        // Convert `<>` links into just stand-alone links.
        $mrkdwn = preg_replace('/(?<=^|[\s;,(])\<((?:[a-z][a-z0-9+.\-]*\:|#)[^\s<>]+)\>(?=$|[\s.!?;,)])/ui', '$1', $mrkdwn);

        // Convert GFM headings into Slack `*bold*`.
        $mrkdwn = preg_replace('/(^|\h+)#+\h+([^#\v]+?)(?:\h+#+)?(?:\h+\{[^{}]*\})?$/uim', '$1*$2*', $mrkdwn);

        // Escape special HTML chars; i.e., convert to plain text now.
        $mrkdwn = $this->c::escHtmlChars($mrkdwn); // After `<>` links & issue references.

        // Replace images (not Slack compatible) with clickable links.
        $mrkdwn = preg_replace_callback('/\!?\[(?:(?R)|[^[\]]*)\]\((?:[a-z][a-z0-9+.\-]*\:|#)[^\s()]+\)(?:\h+\{[^{}]*\})?/ui', function ($m) {
            if (mb_stripos($m[0], '![') === false) {
                return $m[0]; // Not an image.
            } else {
                return preg_replace_callback('/\!\[(?<alt_text>[^[\]]*)\]\((?<url>(?:[a-z][a-z0-9+.\-]*\:|#)[^\s()]+)\)/ui', function ($m) {
                    return '<'.$m['url'].'|'.$this->c::escHtmlChars($m['alt_text'] ? $m['alt_text'].' '.__('(image)') : $this->c::midClip(basename($m['url']), 15)).'>';
                }, $m[0]); // Strip away images.
            }
        }, $mrkdwn); // Strip away images.

        // Convert links for Slack compatibility.
        $mrkdwn = preg_replace_callback('/(?<=^|[\s;,(])\[(?<text>[^|[\]]*)\]\((?<url>(?:[a-z][a-z0-9+.\-]*\:|#)[^|\s()]+)\)(?=$|[\s.!?;,)])/ui', function ($m) {
            if (isset($m['text'][0])) {
                return '<'.$m['url'].'|'.$this->c::escHtmlChars($m['text']).'>';
            } else {
                return '<'.$m['url'].'>';
            }
        }, $mrkdwn); // `<url|text>`

        // No more than two consecutive breaks.
        $mrkdwn = $this->c::mbTrim($this->c::normalizeEols($mrkdwn, true));

        return $mrkdwn = &$Tokenizer->restoreGetString();
    }
}
