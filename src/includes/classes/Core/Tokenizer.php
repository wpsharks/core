<?php
/**
 * Tokenizer.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
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
 * @since 150424 Initial release.
 */
class Tokenizer extends Classes\Core\Base\Core implements Interfaces\UrlConstants
{
    /**
     * ID.
     *
     * @since 150424
     *
     * @type string
     */
    protected $id;

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
     * Behavioral args.
     *
     * @since 160720
     *
     * @type array
     */
    protected $args;

    /**
     * Tokens.
     *
     * @since 150424
     *
     * @type array
     */
    protected $tokens;

    /**
     * A shortcode tag name.
     *
     * @since 160720
     *
     * @type string|null
     */
    protected $shortcode_unautop_compat_tag_name;

    /**
     * Escape char patterns.
     *
     * @since 170211.63148
     *
     * @type array|null
     */
    protected static $m0_esc_no_vws;

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

        if (!isset(static::$m0_esc_no_vws)) {
            static::$m0_esc_no_vws['[]'] = $this->c::regexM0EscNoVws('[]');
            static::$m0_esc_no_vws['()'] = $this->c::regexM0EscNoVws('()');
            static::$m0_esc_no_vws['{}'] = $this->c::regexM0EscNoVws('{}');
        }
        if (!$this->string || !$this->tokenize) {
            return; // Nothing to do.
        }
        $this->maybeTokenizeShortcodes();
        $this->maybeTokenizeIfCondTags();

        $this->maybeTokenizePreTags();
        $this->maybeTokenizeCodeTags();
        $this->maybeTokenizeSampTags();

        $this->maybeTokenizeStyleTags();
        $this->maybeTokenizeScriptTags();

        $this->maybeTokenizeTextareaTags();
        $this->maybeTokenizeAnchorTags();

        $this->maybeTokenizeAllTags();
        $this->maybeTokenizeSpecialAttrs();

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
        } elseif (mb_strpos($this->string, ']') === false) {
            return; // No `[` shortcodes.
        }
        if ($this->args['shortcode_tag_names']) {
            $shortcode_tag_names = $this->args['shortcode_tag_names'];
        } elseif (!empty($GLOBALS['shortcode_tags']) && is_array($GLOBALS['shortcode_tags'])) {
            $shortcode_tag_names = array_keys($GLOBALS['shortcode_tags']);
        }
        if (empty($shortcode_tag_names) || !$this->c::isWordPress() || !$this->c::canCallFunc('get_shortcode_regex')) {
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
                return $m[0]; // Exclude from tokenization.
            }
            $this->tokens[] = $m[0]; // Original data for token.
            // i.e., The entire shortcode (w/ possible escape brackets).

            if ($this->args['shortcode_unautop_compat']) {
                return $token = '['.$this->shortcode_unautop_compat_tag_name.' _is_token=⁅⒯⁆]'.
                                    '⁅⒯'.$this->id.'⒯'.(count($this->tokens) - 1).'⒯⁆'.
                                '[/'.$this->shortcode_unautop_compat_tag_name.']';
            } else { // Default behavior.
                return $token = '⁅⒯'.$this->id.'⒯'.(count($this->tokens) - 1).'⒯⁆';
            }
        }, $this->string); // Shortcodes replaced by tokens.
    }

    /**
     * Maybe tokenize `<!--[if`, `<![if` tags.
     *
     * @since 170824.30708 Initial release.
     */
    protected function maybeTokenizeIfCondTags()
    {
        if (!in_array('if-conds', $this->tokenize, true)) {
            return; // Not tokenizing these.
        } elseif (mb_stripos($this->string, '<!--[') === false && mb_stripos($this->string, '<![') === false) {
            return; // Nothing to tokenize here.
        }
        $regex = '/\<\![^[>]*?\[if\W[^\]]*?\][^>]*?\>(?s:.*?)\<\![^[>]*?\[endif\][^>]*?\>/ui';

        $this->string = preg_replace_callback($regex, function ($m) {
            $this->tokens[] = $m[0];  // Original data for token.
            return $token = '⁅⒯'.$this->id.'⒯'.(count($this->tokens) - 1).'⒯⁆';
        }, $this->string); // Tags replaced by tokens.
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
        $regex = '/\<(pre)(?:\>|\s[^>]*\>)(?:(?s:[^<>]+|(?!\<\\1[\s>]).)+?|(?R))*?\<\/\\1\>/ui';

        $this->string = preg_replace_callback($regex, function ($m) {
            $this->tokens[] = $m[0]; // Original data for token.
            return $token = '⁅⒯'.$this->id.'⒯'.(count($this->tokens) - 1).'⒯⁆';
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
        $regex = '/\<(code)(?:\>|\s[^>]*\>)(?:(?s:[^<>]+|(?!\<\\1[\s>]).)+?|(?R))*?\<\/\\1\>/ui';

        $this->string = preg_replace_callback($regex, function ($m) {
            $this->tokens[] = $m[0];  // Original data for token.
            return $token = '⁅⒯'.$this->id.'⒯'.(count($this->tokens) - 1).'⒯⁆';
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
        $regex = '/\<(samp)(?:\>|\s[^>]*\>)(?:(?s:[^<>]+|(?!\<\\1[\s>]).)+?|(?R))*?\<\/\\1\>/ui';

        $this->string = preg_replace_callback($regex, function ($m) {
            $this->tokens[] = $m[0];  // Original data for token.
            return $token = '⁅⒯'.$this->id.'⒯'.(count($this->tokens) - 1).'⒯⁆';
        }, $this->string); // Tags replaced by tokens.
    }

    /**
     * Maybe tokenize `<style>` tags.
     *
     * @since 170824.30708 Initial release.
     */
    protected function maybeTokenizeStyleTags()
    {
        if (!in_array('style', $this->tokenize, true)) {
            return; // Not tokenizing these.
        } elseif (mb_stripos($this->string, '<style') === false) {
            return; // Nothing to tokenize here.
        }
        $regex = '/\<(style)(?:\>|\s[^>]*\>)(?:(?s:[^<>]+|(?!\<\\1[\s>]).)+?|(?R))*?\<\/\\1\>/ui';

        $this->string = preg_replace_callback($regex, function ($m) {
            $this->tokens[] = $m[0];  // Original data for token.
            return $token = '⁅⒯'.$this->id.'⒯'.(count($this->tokens) - 1).'⒯⁆';
        }, $this->string); // Tags replaced by tokens.
    }

    /**
     * Maybe tokenize `<script>` tags.
     *
     * @since 170824.30708 Initial release.
     */
    protected function maybeTokenizeScriptTags()
    {
        if (!in_array('script', $this->tokenize, true)) {
            return; // Not tokenizing these.
        } elseif (mb_stripos($this->string, '<script') === false) {
            return; // Nothing to tokenize here.
        }
        $regex = '/\<(script)(?:\>|\s[^>]*\>)(?:(?s:[^<>]+|(?!\<\\1[\s>]).)+?|(?R))*?\<\/\\1\>/ui';

        $this->string = preg_replace_callback($regex, function ($m) {
            $this->tokens[] = $m[0];  // Original data for token.
            return $token = '⁅⒯'.$this->id.'⒯'.(count($this->tokens) - 1).'⒯⁆';
        }, $this->string); // Tags replaced by tokens.
    }

    /**
     * Maybe tokenize `<textarea>` tags.
     *
     * @since 170824.30708 Initial release.
     */
    protected function maybeTokenizeTextareaTags()
    {
        if (!in_array('textarea', $this->tokenize, true)) {
            return; // Not tokenizing these.
        } elseif (mb_stripos($this->string, '<textarea') === false) {
            return; // Nothing to tokenize here.
        }
        $regex = '/\<(textarea)(?:\>|\s[^>]*\>)(?:(?s:[^<>]+|(?!\<\\1[\s>]).)+?|(?R))*?\<\/\\1\>/ui';

        $this->string = preg_replace_callback($regex, function ($m) {
            $this->tokens[] = $m[0];  // Original data for token.
            return $token = '⁅⒯'.$this->id.'⒯'.(count($this->tokens) - 1).'⒯⁆';
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
        $regex = '/\<(a)(?:\>|\s[^>]*\>)(?:(?s:[^<>]+|(?!\<\\1[\s>]).)+?|(?R))*?\<\/\\1\>/ui';

        $this->string = preg_replace_callback($regex, function ($m) {
            $this->tokens[] = $m[0];  // Original data for token.
            return $token = '⁅⒯'.$this->id.'⒯'.(count($this->tokens) - 1).'⒯⁆';
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
        $regex = '/\<\/?[\w\-]+(?:\>|\s[^>]*\>)/ui';

        $this->string = preg_replace_callback($regex, function ($m) {
            $this->tokens[] = $m[0];  // Original data for token.
            return $token = '⁅⒯'.$this->id.'⒯'.(count($this->tokens) - 1).'⒯⁆';
        }, $this->string); // Tags replaced by tokens.
    }

    /**
     * Maybe tokenize `<x style|on*|data-*="">` attrs.
     *
     * @since 170824.30708 Initial release.
     */
    protected function maybeTokenizeSpecialAttrs()
    {
        if (!in_array('s-attrs', $this->tokenize, true)) {
            return; // Not tokenizing these.
        } elseif (mb_stripos($this->string, '<') === false) {
            return; // Nothing to tokenize here.
        }
        $regex = '/(?<=\s)(?:style|on[a-z]+|data\-[a-z\-]+)\s*\=\s*(["\'])(?s:.*?)\\1/ui';

        $this->string = preg_replace_callback($regex, function ($m) {
            $this->tokens[] = $m[0];  // Original data for token.
            return $token = '⁅⒯'.$this->id.'⒯'.(count($this->tokens) - 1).'⒯⁆';
        }, $this->string); // Attributes replaced by tokens.
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
        $regex = '/(~{3,}|`{3,}|``|`)(?s:.*?)[^`]\\1/ui';
        // Note: this covers ``a literal `tick inside`` also.
        // See: <https://daringfireball.net/projects/markdown/syntax#code>
        // This also picks up ```lang {attributes} in fenced code blocks.

        $this->string = preg_replace_callback($regex, function ($m) {
            $this->tokens[] = $m[0];  // Original data for token.
            return $token = '⁅⒯'.$this->id.'⒯'.(count($this->tokens) - 1).'⒯⁆';
        }, $this->string); // Fences replaced by tokens.
    }

    /**
     * Maybe tokenize `[md](links)`, et al.
     *
     * @since 150424 Initial release.
     */
    protected function maybeTokenizeMdLinks()
    {
        if (!in_array('md-links', $this->tokenize, true)) {
            return; // Not tokenizing these.
        }
        $_ = static::$m0_esc_no_vws; // Shorter reference.

        $this->string = preg_replace_callback(
            [
                // This one covers MD [links](), [link][references], and ![](images).
                // The negative lookahed for `(?!\/)` is to avoid catching a tokenized shortcode.
                // e.g., Whenever the `shortcode_unautop_compat` configuration option has been enabled.
                '/\!?\[(?:'.$_['[]'].'|(?R))\](?:\h*\[(?!\/)'.$_['[]'].'\]|\('.$_['()'].'\))(?:\h*\{'.$_['{}'].'\})?/ui',

                // In these, we should only tokenize the definition markers and the `<link>` in link definitions.
                '/^\h{0,3}(?:\['.$_['[]'].'\])+\h*\:\h*(?:\<)?[^\s<>]*(?:\>)?/uim', // Link definition markers.
                '/^\h{0,3}(?:\*\['.$_['[]'].'\])+\h*\:/uim', // Abbreviation definition markers.
                '/^\h{0,3}(?:\[\^[0-9]+\])+\h*\:/uim', // Footnote definition markers.

                // Also tokenize MD `<urls>` in brackets.
                '/\<((?:[a-zA-Z][a-zA-Z0-9+.\-]*:)?\/{2}[^\v<>]*)\>/ui',

                // Also tokenize checkbox markers.
                '/^\h*(?:[*+\-]|[0-9]+\.)\h+\[[\sx]\]/uim',
            ],
            function ($m) {
                $this->tokens[] = $m[0];  // Original data for token.
                return $token = '⁅⒯'.$this->id.'⒯'.(count($this->tokens) - 1).'⒯⁆';
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
        if (!$this->tokens || mb_strpos($this->string, '⒯') === false) {
            return $this->string; // Nothing to restore in this case.
        }
        $restore_shortcode_tokens = $this->args['shortcode_unautop_compat']
            && $this->shortcode_unautop_compat_tag_name && in_array('shortcodes', $this->tokenize, true);

        foreach (array_reverse($this->tokens, true) as $_token_id => $_original) {
            // Must go in reverse order so nested tokens unfold properly.
            // If `$restore_shortcode_tokens`, first replace shortcode tokens.

            $_token = '⁅⒯'.$this->id.'⒯'.$_token_id.'⒯⁆'; // Placeholder.

            if ($restore_shortcode_tokens) { // Restoring shortcode tokens in this class instance?
                $this->string = str_replace('['.$this->shortcode_unautop_compat_tag_name.' _is_token=⁅⒯⁆]'.
                                                $_token.// Token inside the shortcode token.
                                            '[/'.$this->shortcode_unautop_compat_tag_name.']', $_original, $this->string);
            }
            $this->string = str_replace($_token, $_original, $this->string); // Always.
        } // unset($_token_id, $_token, $_original); // Housekeeping.

        return $this->string;
    }
}
