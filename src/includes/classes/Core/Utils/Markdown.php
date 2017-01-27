<?php
/**
 * Markdown utilities.
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
#
use ParsedownExtra;
use Michelf\MarkdownExtra;

/**
 * Markdown utilities.
 *
 * @since 150424 Initial release.
 */
class Markdown extends Classes\Core\Base\Core
{
    /**
     * A very simple markdown parser.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value Any input value.
     * @param array $args  Any additional behavioral args.
     *
     * @return string|array|object Html markup value(s).
     */
    public function __invoke($value, array $args = [])
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($_value, $args);
            } //unset($_key, $_value);
            return $value;
        }
        if (!($string = c::mbTrim((string) $value))) {
            return $string; // Nothing to do.
        }
        $default_args = [ // Defaults.
            'cache'               => false,
            'cache_expires_after' => '30 days',

            'flavor' => 'markdown-extra',

            'code_class_prefix' => 'language-',
            'fn_id_prefix'      => '',

            'no_p'      => false,
            'hard_wrap' => true,
            'breaks'    => true,

            'anchorize'   => false,
            'anchor_rels' => [],
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        $cache               = (bool) $args['cache'];
        $cache_expires_after = (string) $args['cache_expires_after'];

        $flavor = (string) $args['flavor'];

        $code_class_prefix = (string) $args['code_class_prefix'];
        $fn_id_prefix      = (string) $args['fn_id_prefix'];

        $no_p      = (bool) $args['no_p'];
        $hard_wrap = (bool) $args['hard_wrap'];
        $breaks    = (bool) $args['breaks'];

        $anchorize   = (bool) $args['anchorize'];
        $anchor_rels = (array) $args['anchor_rels'];

        if ($cache) { // Cache markdown?
            $cache_sha1          = sha1($string.$post_id.serialize($args));
            $cache_sha1_shard_id = $this->c::sha1ModShardId($cache_sha1, true);

            $cache_dir             = $this->App->Config->©fs_paths['©cache_dir'].'/markdown/'.$cache_sha1_shard_id;
            $cache_dir_permissions = $this->App->Config->©fs_permissions['©transient_dirs'];
            $cache_file            = $cache_dir.'/'.$cache_sha1.'.html';

            if (is_file($cache_file) && filemtime($cache_file) >= strtotime('-'.$cache_expires_after)) {
                return (string) file_get_contents($cache_file);
            } // Use the already-cached HTML markup.
        }
        if ($flavor === 'parsedown-extra') {
            if (!($ParsedownExtra = &$this->cacheKey(__FUNCTION__, $flavor))) {
                $ParsedownExtra = new ParsedownExtra();
            } // @TODO Try to support all options.
            $ParsedownExtra->setBreaksEnabled($breaks || $hard_wrap);
            $string = $ParsedownExtra->text($string);
            //
        } else { // Default is Markdown Extra (supports all options).
            $flavor = 'markdown-extra'; // Force default flavor.

            if (!($MarkdownExtra = &$this->cacheKey(__FUNCTION__, $flavor))) {
                $MarkdownExtra                    = new MarkdownExtra();
                $MarkdownExtra->code_class_prefix = $code_class_prefix;
                $MarkdownExtra->fn_id_prefix      = $fn_id_prefix;
                $MarkdownExtra->hard_wrap         = $hard_wrap || $breaks;
            }
            $string = $MarkdownExtra->transform($string);
        }
        if ($anchorize) {
            $string = $this->c::htmlAnchorize($string);
        }
        if ($anchor_rels) {
            $string = $this->c::htmlAnchorRels($string, $anchor_rels);
        }
        if ($no_p) { // Strip ` ^<p>|</p>$ ` tags?
            $string = preg_replace('/^\s*(?:\<p(?:\s[^>]*)?\>)+|(?:\<\/p\>)+\s*$/ui', '', $string);
        }
        if ($cache && isset($cache_dir, $cache_dir_permissions, $cache_file)) {
            if (!is_dir($cache_dir)) {
                mkdir($cache_dir, $cache_dir_permissions, true);

                if (!is_dir($cache_dir)) {
                    debug(0, c::issue(vars(), 'Unable to create cache directory.'));
                    return $string; // Soft failure.
                }
            } // Cache directory exists.
            file_put_contents($cache_file, $string);
        }
        return $string; // HTML markup now.
    }

    /**
     * First image URL.
     *
     * @since 170124.74961 Initial release.
     *
     * @param string $markdown Markdown.
     *
     * @return string First image URL.
     */
    public function firstImageUrl(string $markdown): string
    {
        $regex      = '/(?<=^|[\s;,])\!\[[^[\]]*\]\((?<url>https?\:\/\/[^\s()]+?\.(?:png|jpeg|jpg|gif))\)(?=$|[\s.!?;,])/ui';
        return $url = $markdown && preg_match($regex, $markdown, $_m) ? $_m['url'] : '';
    }

    /**
     * Markdown stripper. @TODO.
     *
     * @since 151029 Markdown stripper.
     *
     * @param mixed $value Any input value.
     * @param array $args  Any additional behavioral args.
     *
     * @return string|array|object Stripped markdown value(s).
     */
    public function strip($value, array $args = [])
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->strip($_value, $args);
            } // unset($_key, $_value);
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        $default_args = []; // None at this time.

        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        // Strip bold, italic, and strikethrough markers.
        $string = preg_replace('/(\*+)([^*]*)\\1/u', '${2}', $string);
        $string = preg_replace('/(_+)([^_]*)\\1/u', '${2}', $string);
        $string = preg_replace('/(~+)([^~]*)\\1/u', '${2}', $string);

        // Strip inline code and/or code blocks.
        $string = preg_replace('/(`+)(?:\w+'."[\r\n]".')?([^`]*)\\1'."[\r\n]".'?/u', '${2}', $string);

        // Strip blockquotes.
        $string = preg_replace('/^\s*\>\s+/um', '', $string);

        // Strip ATX-style headings.
        $string = preg_replace('/^\s*#+\s+([^#]*)#*$/um', '${1}', $string);

        // Strip line-based decorative headings.
        $string = preg_replace('/^\s*[=\-]+\s*$/um', '', $string);

        // Strip images and links.
        $string = preg_replace('/!?\[([^[\]]*)\]\([^()]*\)/u', '${1}', $string);

        return $string;
    }
}
