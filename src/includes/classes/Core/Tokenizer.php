<?php
/**
 * Tokenizer.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Tokenizer.
 *
 * @TODO Add support for recursion in regex patterns.
 * i.e., Do a better job of dealing with nested tags.
 *
 * @since 150424 Initial release.
 */
class Tokenizer extends Classes\Core\Base\Core
{
    /**
     * ID.
     *
     * @since 150424
     *
     * @var string
     */
    protected $id;

    /**
     * String.
     *
     * @since 150424
     *
     * @var string
     */
    protected $string;

    /**
     * Tokenize what?
     *
     * @since 150424
     *
     * @var array
     */
    protected $tokenize;

    /**
     * Behavioral args.
     *
     * @since 160720
     *
     * @var array
     */
    protected $args;

    /**
     * Tokens.
     *
     * @since 150424
     *
     * @var array
     */
    protected $tokens;

    /**
     * A shortcode tag name.
     *
     * @since 160720
     *
     * @var string|null
     */
    protected $shortcode_unautop_compat_tag_name;

    /**
     * Tokenize specific elements.
     *
     * @since 150424 Initial release.
     *
     * @param Classes\App $App      Instance of App.
     * @param string      $string   Input string.
     * @param array       $tokenize Specific elements.
     * @param array       $args     Any behavioral args.
     *
     * @return string The tokenized string.
     */
    public function __construct(Classes\App $App, string $string, array $tokenize, array $args = [])
    {
        parent::__construct($App);

        $this->id       = $this->c::uniqueId();
        $this->string   = $string; // String to tokenize.
        $this->tokenize = $tokenize; // What to tokenize.

        $default_args = [
            'shortcode_tag_names'               => [],
            'exclude_escaped_shortcode'         => false,
            'shortcode_unautop_compat'          => false,
            'shortcode_unautop_compat_tag_name' => '',
        ]; // Establishes argument defaults.

        $this->args                                      = $args + $default_args;
        $this->args['shortcode_tag_names']               = (array) $this->args['shortcode_tag_names'];
        $this->args['exclude_escaped_shortcode']         = (bool) $this->args['exclude_escaped_shortcode'];
        $this->args['shortcode_unautop_compat']          = (bool) $this->args['shortcode_unautop_compat'];
        $this->args['shortcode_unautop_compat_tag_name'] = (string) $this->args['shortcode_unautop_compat_tag_name'];

        $this->tokens = []; // Initialize tokens.

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
     * Get tokenizer ID.
     *
     * @since 160720 Initial release.
     *
     * @return string Tokenizer ID.
     */
    public function getId(): string
    {
        return $this->id;
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
     * Set tokenized string.
     *
     * @since 150424 Initial release.
     *
     * @param string Set the tokenized string.
     */
    public function setString(string $string)
    {
        $this->string = $string;
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
        } elseif (mb_strpos($this->string, '[') === false) {
            return; // No `[` shortcodes.
        }
        if ($this->args['shortcode_tag_names']) {
            $shortcode_tag_names = $this->args['shortcode_tag_names'];
        } elseif (!empty($GLOBALS['shortcode_tags']) && is_array($GLOBALS['shortcode_tags'])) {
            $shortcode_tag_names = array_keys($GLOBALS['shortcode_tags']);
        }
        if (empty($shortcode_tag_names) || !$this->c::isWordPress()
                || !$this->c::canCallFunc('get_shortcode_regex')) {
            return; // Not possible at this time.
        }
        if ($this->args['shortcode_unautop_compat_tag_name']) {
            $this->shortcode_unautop_compat_tag_name = $this->args['shortcode_unautop_compat_tag_name'];
        } else { // Any registered shortcode tag name; for `shortcode_unautop_compat` mode.
            $this->shortcode_unautop_compat_tag_name = $shortcode_tag_names[0];
        } // This prevents WordPress from wrapping a stand-alone token with `<p></p>`.

        $regex = '/'.get_shortcode_regex($shortcode_tag_names).'/us'; // Dot matches new line.
        // See: <https://developer.wordpress.org/reference/functions/get_shortcode_regex/>

        $this->string = preg_replace_callback($regex, function ($m) {
            // If excluding escaped shortcodes, check here before we continue.
            if ($this->args['exclude_escaped_shortcode'] && $m[1] === '[' && $m[6] === ']') {
                return $m[0]; // Escaped; exclude from tokenization.
            }
            $this->tokens[] = $m[0]; // Original data for token.
            // i.e., The entire shortcode (w/ possible escape brackets).

            if ($this->args['shortcode_unautop_compat']) {
                return $token = '['.$this->shortcode_unautop_compat_tag_name.' _is_token="|%#%|"]'.
                                    '|%#%|'.$this->id.'-'.(count($this->tokens) - 1).'|%#%|'.
                                '[/'.$this->shortcode_unautop_compat_tag_name.']';
            } else { // Default behavior.
                return $token = '|%#%|'.$this->id.'-'.(count($this->tokens) - 1).'|%#%|';
            }
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
        } elseif (mb_stripos($this->string, '<pre') === false) {
            return; // Nothing to tokenize here.
        }
        $regex = '/'.// HTML `<pre>` tags.

            '(?<tag_open_bracket>\<)'.// Opening `<` bracket.
            '(?<tag_open_name>pre)'.// Tag name; i.e., a `pre` tag.
            '(?<tag_open_attrs_bracket>\>|\s[^>]*\>)'.// Attributes & `>`.
            '(?<tag_contents>.*?)'.// Tag contents (multiline possible).
            '(?<tag_close>\<\/\\2\>)'.// Closing `</pre>` tag.

        '/uis'; // End of regex pattern; plus modifiers.

        $this->string = preg_replace_callback($regex, function ($m) {
            $this->tokens[] = $m[0]; // Original data for token.
            return $token = '|%#%|'.$this->id.'-'.(count($this->tokens) - 1).'|%#%|';
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
        } elseif (mb_stripos($this->string, '<code') === false) {
            return; // Nothing to tokenize here.
        }
        $regex = '/'.// HTML `<code>` tags.

            '(?<tag_open_bracket>\<)'.// Opening `<` bracket.
            '(?<tag_open_name>code)'.// Tag name; i.e., a `code` tag.
            '(?<tag_open_attrs_bracket>\>|\s[^>]*\>)'.// Attributes & `>`.
            '(?<tag_contents>.*?)'.// Tag contents (multiline possible).
            '(?<tag_close>\<\/\\2\>)'.// Closing `</code>` tag.

        '/uis'; // End of regex pattern; plus modifiers.

        $this->string = preg_replace_callback($regex, function ($m) {
            $this->tokens[] = $m[0];  // Original data for token.
            return $token = '|%#%|'.$this->id.'-'.(count($this->tokens) - 1).'|%#%|';
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
        } elseif (mb_stripos($this->string, '<samp') === false) {
            return; // Nothing to tokenize here.
        }
        $regex = '/'.// HTML `<samp>` tags.

            '(?<tag_open_bracket>\<)'.// Opening `<` bracket.
            '(?<tag_open_name>samp)'.// Tag name; i.e., a `samp` tag.
            '(?<tag_open_attrs_bracket>\>|\s[^>]*\>)'.// Attributes & `>`.
            '(?<tag_contents>.*?)'.// Tag contents (multiline possible).
            '(?<tag_close>\<\/\\2\>)'.// Closing `</samp>` tag.

        '/uis'; // End of regex pattern; plus modifiers.

        $this->string = preg_replace_callback($regex, function ($m) {
            $this->tokens[] = $m[0];  // Original data for token.
            return $token = '|%#%|'.$this->id.'-'.(count($this->tokens) - 1).'|%#%|';
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
        } elseif (mb_stripos($this->string, '<a') === false) {
            return; // Nothing to tokenize here.
        }
        $regex = '/'.// HTML `<samp>` tags.

            '(?<tag_open_bracket>\<)'.// Opening `<` bracket.
            '(?<tag_open_name>a)'.// Tag name; i.e., an `a` tag.
            '(?<tag_open_attrs_bracket>\>|\s[^>]*\>)'.// Attributes & `>`.
            '(?<tag_contents>.*?)'.// Tag contents (multiline possible).
            '(?<tag_close>\<\/\\2\>)'.// Closing `</a>` tag.

        '/uis'; // End of regex pattern; plus modifiers.

        $this->string = preg_replace_callback($regex, function ($m) {
            $this->tokens[] = $m[0];  // Original data for token.
            return $token = '|%#%|'.$this->id.'-'.(count($this->tokens) - 1).'|%#%|';
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
        } elseif (mb_stripos($this->string, '<') === false) {
            return; // Nothing to tokenize here.
        }
        $regex = '/'.// HTML `<a-z0-9>` tags (i.e., tags only).

            '(?<tag_open_close_bracket>\<\/?)'.// Open or close `<[/]` bracket.
            '(?<tag_open_close_name>[a-z0-9]+)'.// See: <http://jas.xyz/1P1MQyh>
            '(?<tag_open_close_attrs_bracket>\>|\s[^>]*\>)'.// Attributes & `>`.

        '/ui'; // End of regex pattern; plus modifiers.

        $this->string = preg_replace_callback($regex, function ($m) {
            $this->tokens[] = $m[0];  // Original data for token.
            return $token = '|%#%|'.$this->id.'-'.(count($this->tokens) - 1).'|%#%|';
        }, $this->string); // Tags replaced by tokens.
    }

    /**
     * Maybe tokenize MD fences.
     *
     * @since 150424 Initial release.
     */
    protected function maybeTokenizeMdFences()
    {
        if (!in_array('md-fences', $this->tokenize, true)) {
            return; // Not tokenizing these.
        } elseif (mb_strpos($this->string, '~') === false && mb_strpos($this->string, '`') === false) {
            return; // Nothing to tokenize here.
        }
        $regex = '/'.// Markdown pre/code fences.

            '(?<fence_open>~{3,}|`{3,}|`)'.// Opening fence.
            '(?<fence_contents>.*?)'.// Contents (multiline possible).
            '(?<fence_close>\\1)'.// Closing fence; ~~~, ```, `.

        '/uis'; // End of regex pattern; plus modifiers.

        $this->string = preg_replace_callback($regex, function ($m) {
            $this->tokens[] = $m[0];  // Original data for token.
            return $token = '|%#%|'.$this->id.'-'.(count($this->tokens) - 1).'|%#%|';
        }, $this->string); // Fences replaced by tokens.
    }

    /**
     * Maybe tokenize `[markdown](links)`.
     *
     * @since 150424 Initial release.
     */
    protected function maybeTokenizeMdLinks()
    {
        if (!in_array('md-links', $this->tokenize, true)) {
            return; // Not tokenizing these.
        }
        // This also tokenizes [Markdown]: <link> "definitions".
        // This routine includes considerations for images also.

        // The tokenizer does NOT deal with links that reference definitions, as this is not necessary.
        // So, while we DO tokenize <link> "definitions" themselves, the [actual][references] to
        // these definitions do not need to be tokenized; i.e., it is not necessary here.

        $this->string = preg_replace_callback(
            [
                '/^[ ]*(?:\[[^\]]+\])+[ ]*\:[ ]*(?:\<[^>]+\>|\S+)(?:[ ]+.+)?$/um',
                '/\!?\[(?:(?R)|[^\]]*)\]\([^)]+\)(?:\{[^}]*\})?/u',
            ],
            function ($m) {
                $this->tokens[] = $m[0];  // Original data for token.
                return $token = '|%#%|'.$this->id.'-'.(count($this->tokens) - 1).'|%#%|';
            },
            $this->string // Shortcodes replaced by tokens.
        );
    }

    /**
     * Restore token originals.
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
        $restore_shortcode_tokens = $this->args['shortcode_unautop_compat']
            && $this->shortcode_unautop_compat_tag_name && in_array('shortcodes', $this->tokenize, true);

        foreach (array_reverse($this->tokens, true) as $_token_id => $_original) {
            // Must go in reverse order so nested tokens unfold properly.
            // If `$restore_shortcode_tokens`, first replace shortcode tokens.

            $_token = '|%#%|'.$this->id.'-'.$_token_id.'|%#%|'; // Placeholder.

            if ($restore_shortcode_tokens) { // Restoring shortcode tokens in this class instance?
                $this->string = str_replace('['.$this->shortcode_unautop_compat_tag_name.' _is_token="|%#%|"]'.
                                                $_token.// Token inside the shortcode token.
                                            '[/'.$this->shortcode_unautop_compat_tag_name.']', $_original, $this->string);
            }
            $this->string = str_replace($_token, $_original, $this->string);
        } // unset($_token_id, $_token, $_original); // Housekeeping.

        return $this->string;
    }
}
