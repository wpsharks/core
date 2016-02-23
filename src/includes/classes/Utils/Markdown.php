<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use ParsedownExtra;
use Michelf\MarkdownExtra;

/**
 * Markdown utilities.
 *
 * @since 150424 Initial release.
 */
class Markdown extends Classes\Core
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
    public function __invoke($value, array $args = array())
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($_value, $args);
            } //unset($_key, $_value);
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        $default_args = [
            'flavor' => 'markdown-extra',
            // `parsedown-extra` is faster, but buggy.
            // See: <https://github.com/erusev/parsedown-extra/issues/44>
            'breaks'      => true, // Parsedown only.
            'anchorize'   => false,
            'anchor_rels' => [],
            'no_p'        => false,
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        $flavor      = (string) $args['flavor'];
        $breaks      = (bool) $args['breaks'];
        $no_p        = (bool) $args['no_p'];
        $anchorize   = (bool) $args['anchorize'];
        $anchor_rels = (array) $args['anchor_rels'];

        if ($flavor === 'parsedown-extra') {
            if (is_null($ParsedownExtra = &$this->cacheKey(__FUNCTION__, $flavor))) {
                $ParsedownExtra = new ParsedownExtra();
            }
            $ParsedownExtra->setBreaksEnabled($breaks);
            $string = $ParsedownExtra->text($string);
        } else {
            $flavor = 'markdown-extra'; // Default flavor.
            if (is_null($MarkdownExtra = &$this->cacheKey(__FUNCTION__, $flavor))) {
                $MarkdownExtra                    = new MarkdownExtra();
                $MarkdownExtra->code_class_prefix = 'language-';
            }
            $string = $MarkdownExtra->transform($string);
        }
        if ($anchorize) {
            $string = c\html_anchorize($string);
        }
        if ($anchor_rels) {
            $string = c\html_anchor_rels($string, $anchor_rels);
        }
        if ($no_p) { // Strip ` ^<p>|</p>$ ` tags?
            $string = preg_replace('/^\s*(?:\<p(?:\s[^>]*)?\>)+|(?:\<\/p\>)+\s*$/ui', '', $string);
        }
        return $string;
    }

    /**
     * Markdown stripper.
     *
     * @since 151029 Markdown stripper.
     *
     * @param mixed $value Any input value.
     * @param array $args  Any additional behavioral args.
     *
     * @return string|array|object Stripped markdown value(s).
     */
    public function strip($value, array $args = array())
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

        // Stripe images and links.
        $string = preg_replace('/!?\[([^[\]]*)\]\([^()]*\)/u', '${1}', $string);

        return $string;
    }
}
