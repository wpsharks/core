<?php
/**
 * Markdown utilities.
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
#
use Michelf\MarkdownExtra;
use Michelf\SmartyPants;

/**
 * Markdown utilities.
 *
 * @since 150424 Initial release.
 */
class Markdown extends Classes\Core\Base\Core
{
    /**
     * Header ID counter.
     *
     * @since 17xxxx Enhancing headers.
     *
     * @type array Counter.
     */
    protected $header_id_counter = [];

    /**
     * A very simple markdown parser.
     *
     * @since 150424 Initial release.
     * @since 170211.63148 Removing `parsedown-extra`.
     * @deprecated 170131 Use `hard_wrap` instead of `breaks`.
     * @deprecated 170604 `flavor` no longer available.
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
        if (!($string = $this->c::mbTrim((string) $value))) {
            return $string; // Nothing to do.
        }
        $default_args = [ // Defaults.
            'cache'               => false,
            'cache_expires_after' => '30 days',

            'code_attr_on_pre'  => false,
            'code_class_prefix' => 'lang-',

            'header_id_func'    => null,
            'fn_id_prefix'      => '',

            'no_p'      => false,
            'hard_wrap' => true,
            'breaks'    => true,

            'anchorize'   => false,
            'anchor_rels' => [],

            'smartypants'   => true,
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        $cache               = (bool) $args['cache'];
        $cache_expires_after = (string) $args['cache_expires_after'];

        $code_attr_on_pre  = (bool) $args['code_attr_on_pre'];
        $code_class_prefix = (string) $args['code_class_prefix'];

        $header_id_func = $args['header_id_func'] ?: [$this, 'headerIdCallback'];
        $fn_id_prefix   = (string) $args['fn_id_prefix'];

        $no_p      = (bool) $args['no_p'];
        $hard_wrap = (bool) ($args['hard_wrap'] || $args['breaks']);

        $anchorize   = (bool) $args['anchorize'];
        $anchor_rels = (array) $args['anchor_rels'];

        $smartypants = (bool) $args['smartypants'];

        if ($cache) { // Cache?
            $cache_args = $args;
            // Cannot serialize closures.
            unset($cache_args['header_id_func']);

            $cache_sha1          = sha1($string.$post_id.serialize($cache_args));
            $cache_sha1_shard_id = $this->c::sha1ModShardId($cache_sha1, true);

            $cache_dir             = $this->App->Config->©fs_paths['©cache_dir'].'/markdown/'.$cache_sha1_shard_id;
            $cache_dir_permissions = $this->App->Config->©fs_permissions['©transient_dirs'];
            $cache_file            = $cache_dir.'/'.$cache_sha1.'.html';

            if (is_file($cache_file) && filemtime($cache_file) >= strtotime('-'.$cache_expires_after)) {
                return (string) file_get_contents($cache_file);
            } // Use the already-cached HTML markup.
        }
        if (!($MarkdownExtra = &$this->cacheKey(__FUNCTION__.'extra'))) {
            $MarkdownExtra = new MarkdownExtra(); // New instance.
        }
        if (!($SmartyPants = &$this->cacheKey(__FUNCTION__, 'smartypants'))) {
            $SmartyPants               = new SmartyPants(2); // Match JS libs.
            $SmartyPants->tags_to_skip = 'pre|code|samp|kbd|tt|math|script|style';
            $SmartyPants->decodeEntitiesInConfiguration(); // Use UTF-8 symbols.
        }
        $MarkdownExtra->code_attr_on_pre  = $code_attr_on_pre;
        $MarkdownExtra->code_class_prefix = $code_class_prefix;
        $MarkdownExtra->header_id_func    = $header_id_func;
        $MarkdownExtra->fn_id_prefix      = $fn_id_prefix;
        $MarkdownExtra->hard_wrap         = $hard_wrap;

        $this->header_id_counter = []; // Reset counter.
        $string                  = $MarkdownExtra->transform($string);

        $string = $anchorize ? $this->c::htmlAnchorize($string) : $string;
        $string = $anchor_rels ? $this->c::htmlAnchorRels($string, $anchor_rels) : $string;
        $string = $smartypants ? $SmartyPants->transform($string) : $string;

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
     * Default header `id=""` callback.
     *
     * @since 170211.63148 ID callback.
     *
     * @param string $raw Header text value.
     *
     * @return string The `id=""` attribute value.
     */
    public function headerIdCallback(string $raw): string
    {
        $id = mb_strtolower($raw);
        $id = preg_replace('/[^\w]+/u', '-', $id);
        $id = 'j2h.'.$this->c::mbTrim($id, '', '-');

        $this->header_id_counter[$id] = $this->header_id_counter[$id] ?? 0;
        ++$this->header_id_counter[$id]; // Increment counter.

        if ($this->header_id_counter[$id] > 1) {
            $id .= '-'.$this->header_id_counter[$id];
        }
        return $id; // `id=""` attribute value.
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
        $regex      = '/(?<=^|[\s;,])\!\[[^\v[\]]*\]\((?<url>https?\:\/\/[^\s()]+?\.(?:png|jpeg|jpg|gif))\)(?=$|[\s.!?;,])/ui';
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
