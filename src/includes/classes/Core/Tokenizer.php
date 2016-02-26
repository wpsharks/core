<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Tokenizer.
 *
 * @since 150424 Initial release.
 */
class Tokenizer extends Classes\Core
{
    /**
     * String.
     *
     * @since 150424
     *
     * @type string
     */
    protected $string;

    /**
     * Tokenize what?
     *
     * @since 150424
     *
     * @type array
     */
    protected $tokenize;

    /**
     * Tokens.
     *
     * @since 150424
     *
     * @type array
     */
    protected $tokens;

    /**
     * Marker.
     *
     * @since 150424
     *
     * @type string
     */
    protected $marker;

    /**
     * Tokenize specific elements.
     *
     * @since 150424 Initial release.
     *
     * @param Classes\App $App      Instance of App.
     * @param string      $string   Input string.
     * @param array       $tokenize Specific elements.
     *
     * @return string The tokenized string.
     */
    public function __construct(Classes\App $App, string $string, array $tokenize)
    {
        parent::__construct($App);

        $this->string   = $string;
        $this->tokenize = $tokenize;
        $this->tokens   = []; // Initialize.
        $this->marker   = str_replace('.', '', uniqid('', true));

        if (!$this->string || !$this->tokenize) {
            return; // Nothing to do.
        }
        $this->maybeTokenizeShortcodes();

        $this->maybeTokenizePreTags();
        $this->maybeTokenizeCodeTags();
        $this->maybeTokenizeSampTags();
        $this->maybeTokenizeAnchorTags();
        $this->maybeTokenizeAllTags();

        $this->maybeTokenizeMdFences();
        $this->maybeTokenizeMdLinks();
    }

    /**
     * Get tokenized string.
     *
     * @since 150424 Initial release.
     *
     * @return string Tokenized string (by ref).
     */
    public function &getString(): string
    {
        return $this->string;
    }

    /**
     * Restore tokens.
     *
     * @since 150424 Initial release.
     *
     * @return string After restoring tokens.
     */
    public function &restoreGetString(): string
    {
        if (!$this->tokens || mb_strpos($this->string, '|%#%|') === false) {
            return $this->string; // Nothing to restore in this case.
        }
        foreach (array_reverse($this->tokens, true) as $_token => $_value) {
            // Must go in reverse order so nested tokens unfold properly.
            $this->string = str_replace('|%#%|html-token-'.$this->marker.'-'.$_token.'|%#%|', $_value, $this->string);
        } // unset($_token, $_value); // Housekeeping.

        return $this->string;
    }

    /**
     * Maybe tokenize shortcodes.
     *
     * @since 150424 Initial release.
     */
    protected function maybeTokenizeShortcodes()
    {
        if (!in_array('shortcodes', $this->tokenize, true)) {
            return; // Not tokenizing these.
        }
        if (!$this->a::isWordpress() || empty($GLOBALS['shortcode_tags']) || !$this->a::canCallFunc('get_shortcode_regex')) {
            return; // Not WordPress; i.e., no known shortcodes.
        }
        if (mb_strpos($this->string, '[') === false) {
            return; // No `[` shortcodes.
        }
        $this->string = preg_replace_callback('/'.get_shortcode_regex().'/us', function ($m) {
            $this->tokens[] = $m[0]; // Tokenize.
            return '|%#%|html-token-'.$this->marker.'-'.(count($this->tokens) - 1).'|%#%|';
        }, $this->string); // Shortcodes replaced by tokens.
    }

    /**
     * Maybe tokenize `<pre>` tags.
     *
     * @since 150424 Initial release.
     */
    protected function maybeTokenizePreTags()
    {
        if (!in_array('pre', $this->tokenize, true)) {
            return; // Not tokenizing these.
        }
        if (mb_stripos($this->string, '<pre') === false) {
            return; // Nothing to tokenize here.
        }
        $pre = // HTML `<pre>` tags.
            '/(?<tag_open_bracket>\<)'.// Opening `<` bracket.
            '(?<tag_open_name>pre)'.// Tag name; i.e., a `pre` tag.
            '(?<tag_open_attrs_bracket>\>|\s[^>]*\>)'.// Attributes & `>`.
            '(?<tag_contents>.*?)'.// Tag contents (multiline possible).
            '(?<tag_close>\<\/\\2\>)/uis'; // e.g. closing `</pre>` tag.

        $this->string = preg_replace_callback($pre, function ($m) {
            $this->tokens[] = $m[0]; // Tokenize.
            return '|%#%|html-token-'.$this->marker.'-'.(count($this->tokens) - 1).'|%#%|';
        }, $this->string); // Tags replaced by tokens.
    }

    /**
     * Maybe tokenize `<code>` tags.
     *
     * @since 150424 Initial release.
     */
    protected function maybeTokenizeCodeTags()
    {
        if (!in_array('code', $this->tokenize, true)) {
            return; // Not tokenizing these.
        }
        if (mb_stripos($this->string, '<code') === false) {
            return; // Nothing to tokenize here.
        }
        $code = // HTML `<code>` tags.
            '/(?<tag_open_bracket>\<)'.// Opening `<` bracket.
            '(?<tag_open_name>code)'.// Tag name; i.e., a `code` tag.
            '(?<tag_open_attrs_bracket>\>|\s[^>]*\>)'.// Attributes & `>`.
            '(?<tag_contents>.*?)'.// Tag contents (multiline possible).
            '(?<tag_close>\<\/\\2\>)/uis'; // e.g. closing `</code>` tag.

        $this->string = preg_replace_callback($code, function ($m) {
            $this->tokens[] = $m[0]; // Tokenize.
            return '|%#%|html-token-'.$this->marker.'-'.(count($this->tokens) - 1).'|%#%|';
        }, $this->string); // Tags replaced by tokens.
    }

    /**
     * Maybe tokenize `<samp>` tags.
     *
     * @since 150424 Initial release.
     */
    protected function maybeTokenizeSampTags()
    {
        if (!in_array('samp', $this->tokenize, true)) {
            return; // Not tokenizing these.
        }
        if (mb_stripos($this->string, '<samp') === false) {
            return; // Nothing to tokenize here.
        }
        $samp = // HTML `<samp>` tags.
            '/(?<tag_open_bracket>\<)'.// Opening `<` bracket.
            '(?<tag_open_name>samp)'.// Tag name; i.e., a `samp` tag.
            '(?<tag_open_attrs_bracket>\>|\s[^>]*\>)'.// Attributes & `>`.
            '(?<tag_contents>.*?)'.// Tag contents (multiline possible).
            '(?<tag_close>\<\/\\2\>)/uis'; // e.g. closing `</samp>` tag.

        $this->string = preg_replace_callback($samp, function ($m) {
            $this->tokens[] = $m[0]; // Tokenize.
            return '|%#%|html-token-'.$this->marker.'-'.(count($this->tokens) - 1).'|%#%|';
        }, $this->string); // Tags replaced by tokens.
    }

    /**
     * Maybe tokenize `<a>` tags.
     *
     * @since 150424 Initial release.
     */
    protected function maybeTokenizeAnchorTags()
    {
        if (!in_array('anchors', $this->tokenize, true)) {
            return; // Not tokenizing these.
        }
        if (mb_stripos($this->string, '<a') === false) {
            return; // Nothing to tokenize here.
        }
        $a = // HTML `<samp>` tags.
            '/(?<tag_open_bracket>\<)'.// Opening `<` bracket.
            '(?<tag_open_name>a)'.// Tag name; i.e., an `a` tag.
            '(?<tag_open_attrs_bracket>\>|\s[^>]*\>)'.// Attributes & `>`.
            '(?<tag_contents>.*?)'.// Tag contents (multiline possible).
            '(?<tag_close>\<\/\\2\>)/uis'; // e.g. closing `</a>` tag.

        $this->string = preg_replace_callback($a, function ($m) {
            $this->tokens[] = $m[0]; // Tokenize.
            return '|%#%|html-token-'.$this->marker.'-'.(count($this->tokens) - 1).'|%#%|';
        }, $this->string); // Tags replaced by tokens.
    }

    /**
     * Maybe tokenize all `<tags>`.
     *
     * @since 150424 Initial release.
     */
    protected function maybeTokenizeAllTags()
    {
        if (!in_array('tags', $this->tokenize, true)) {
            return; // Not tokenizing these.
        }
        if (mb_stripos($this->string, '<') === false) {
            return; // Nothing to tokenize here.
        }
        $tags = // This matches HTML `<a-z0-9>` tags (i.e., tags only).
            '/(?<tag_open_close_bracket>\<\/?)'.// Open or close `<[/]` bracket.
            '(?<tag_open_close_name>[a-z0-9]+)'.// See: <http://jas.xyz/1P1MQyh>
            '(?<tag_open_close_attrs_bracket>\>|\s[^>]*\>)/ui'; // Attributes & `>`.

        $this->string = preg_replace_callback($tags, function ($m) {
            $this->tokens[] = $m[0]; // Tokenize.
            return '|%#%|html-token-'.$this->marker.'-'.(count($this->tokens) - 1).'|%#%|';
        }, $this->string); // Tags replaced by tokens.
    }

    /**
     * Maybe tokenize `md fences`.
     *
     * @since 150424 Initial release.
     */
    protected function maybeTokenizeMdFences()
    {
        if (!in_array('md_fences', $this->tokenize, true)) {
            return; // Not tokenizing these.
        }
        if (mb_strpos($this->string, '~') === false && mb_strpos($this->string, '`') === false) {
            return; // Nothing to tokenize here.
        }
        $md_fences = // Markdown pre/code fences.
            '/(?<fence_open>~{3,}|`{3,}|`)'.// Opening fence.
            '(?<fence_contents>.*?)'.// Contents (multiline possible).
            '(?<fence_close>\\1)/uis'; // Closing fence; ~~~, ```, `.

        $this->string = preg_replace_callback($md_fences, function ($m) {
            $this->tokens[] = $m[0]; // Tokenize.
            return '|%#%|html-token-'.$this->marker.'-'.(count($this->tokens) - 1).'|%#%|';
        }, $this->string); // Fences replaced by tokens.
    }

    /**
     * Maybe tokenize `[markdown](links)`.
     *
     * @since 150424 Initial release.
     */
    protected function maybeTokenizeMdLinks()
    {
        // This also tokenizes [Markdown]: <link> "definitions".
        // This routine includes considerations for images also.

        // The tokenizer does NOT deal with links that reference definitions, as this is not necessary.
        // So, while we DO tokenize <link> "definitions" themselves, the [actual][references] to
        // these definitions do not need to be tokenized; i.e., it is not necessary here.

        if (!in_array('md_links', $this->tokenize, true)) {
            return; // Not tokenizing these.
        }
        $this->string = preg_replace_callback(
            [
                '/^[ ]*(?:\[[^\]]+\])+[ ]*\:[ ]*(?:\<[^>]+\>|\S+)(?:[ ]+.+)?$/um',
                '/\!?\[(?:(?R)|[^\]]*)\]\([^)]+\)(?:\{[^}]*\})?/u',
            ],
            function ($m) {
                $this->tokens[] = $m[0]; // Tokenize.
                return '|%#%|html-token-'.$this->marker.'-'.(count($this->tokens) - 1).'|%#%|';
            },
            $this->string // Shortcodes replaced by tokens.
        );
    }
}
