<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * HTML tokenizer utilities.
 *
 * @since 150424 Initial release.
 */
class HtmlTokenizer extends Classes\AbsBase
{
    /**
     * Tokenize specific elements.
     *
     * @since 150424 Initial release.
     *
     * @param string $string   Input string to tokenize.
     * @param array  $tokenize Tokenize specific elements.
     * @param string $marker   Optional marker suffix.
     *
     * @return array Array w/ `string`, `tokens`, `marker`.
     */
    public function tokenize(string $string, array $tokenize, string $marker = ''): array
    {
        $tokenized           = ['string' => $string, 'tokens' => [], 'marker' => ''];
        $tokenized['marker'] = str_replace('.', '', uniqid('', true));
        $tokenized['marker'] .= $marker ? sha1($marker) : '';

        if (!$string || !$tokenize) {
            goto finale; // Nothing to do here.
        }
        shortcodes: // Target point; `[shortcodes]`.

        if (!in_array('shortcodes', $tokenize, true)) {
            goto pre; // Not tokenizing these.
        }
        if (!defined('WPINC') || empty($GLOBALS['shortcode_tags'])
            || !$this->Utils->PhpHas->callableFunction('get_shortcode_regex')
            || mb_strpos($tokenized['string'], '[') === false) {
            goto pre; // No `[` shortcodes.
        }
        $tokenized['string'] = preg_replace_callback('/'.get_shortcode_regex().'/us', function ($m) use (&$tokenized) {
            $tokenized['tokens'][] = $m[0]; // Tokenize.
            return '%#%token-'.$tokenized['marker'].'-'.(count($tokenized['tokens']) - 1).'%#%';
        }, $tokenized['string']); // Shortcodes replaced by tokens.

        pre: // Target point; HTML `<pre>` tags.

        if (!in_array('pre', $tokenize, true)) {
            goto code; // Not tokenizing these.
        }
        if (mb_stripos($tokenized['string'], '<pre') === false) {
            goto code; // Nothing to tokenize here.
        }
        $pre = // HTML `<pre>` tags.
            '/(?<tag_open_bracket>\<)'.// Opening `<` bracket.
            '(?<tag_open_name>pre)'.// Tag name; i.e., a `pre` tag.
            '(?<tag_open_attrs_bracket>\>|\s[^>]*\>)'.// Attributes & `>`.
            '(?<tag_contents>.*?)'.// Tag contents (multiline possible).
            '(?<tag_close>\<\/\\2\>)/uis'; // e.g. closing `</pre>` tag.

        $tokenized['string'] = preg_replace_callback($pre, function ($m) use (&$tokenized) {
            $tokenized['tokens'][] = $m[0]; // Tokenize.
            return '%#%token-'.$tokenized['marker'].'-'.(count($tokenized['tokens']) - 1).'%#%';
        }, $tokenized['string']); // Tags replaced by tokens.

        code: // Target point; HTML `<code>` tags.

        if (!in_array('code', $tokenize, true)) {
            goto samp; // Not tokenizing these.
        }
        if (mb_stripos($tokenized['string'], '<code') === false) {
            goto samp; // Nothing to tokenize here.
        }
        $code = // HTML `<code>` tags.
            '/(?<tag_open_bracket>\<)'.// Opening `<` bracket.
            '(?<tag_open_name>code)'.// Tag name; i.e., a `code` tag.
            '(?<tag_open_attrs_bracket>\>|\s[^>]*\>)'.// Attributes & `>`.
            '(?<tag_contents>.*?)'.// Tag contents (multiline possible).
            '(?<tag_close>\<\/\\2\>)/uis'; // e.g. closing `</code>` tag.

        $tokenized['string'] = preg_replace_callback($code, function ($m) use (&$tokenized) {
            $tokenized['tokens'][] = $m[0]; // Tokenize.
            return '%#%token-'.$tokenized['marker'].'-'.(count($tokenized['tokens']) - 1).'%#%';
        }, $tokenized['string']); // Tags replaced by tokens.

        samp: // Target point; HTML `<samp>` tags.

        if (!in_array('samp', $tokenize, true)) {
            goto a; // Not tokenizing these.
        }
        if (mb_stripos($tokenized['string'], '<samp') === false) {
            goto a; // Nothing to tokenize here.
        }
        $samp = // HTML `<samp>` tags.
            '/(?<tag_open_bracket>\<)'.// Opening `<` bracket.
            '(?<tag_open_name>samp)'.// Tag name; i.e., a `samp` tag.
            '(?<tag_open_attrs_bracket>\>|\s[^>]*\>)'.// Attributes & `>`.
            '(?<tag_contents>.*?)'.// Tag contents (multiline possible).
            '(?<tag_close>\<\/\\2\>)/uis'; // e.g. closing `</samp>` tag.

        $tokenized['string'] = preg_replace_callback($samp, function ($m) use (&$tokenized) {
            $tokenized['tokens'][] = $m[0]; // Tokenize.
            return '%#%token-'.$tokenized['marker'].'-'.(count($tokenized['tokens']) - 1).'%#%';
        }, $tokenized['string']); // Tags replaced by tokens.

        a: // Target point; HTML `<a>` tags.

        if (!in_array('a', $tokenize, true)) {
            goto tags; // Not tokenizing these.
        }
        if (mb_stripos($tokenized['string'], '<a') === false) {
            goto tags; // Nothing to tokenize here.
        }
        $a = // HTML `<samp>` tags.
            '/(?<tag_open_bracket>\<)'.// Opening `<` bracket.
            '(?<tag_open_name>a)'.// Tag name; i.e., an `a` tag.
            '(?<tag_open_attrs_bracket>\>|\s[^>]*\>)'.// Attributes & `>`.
            '(?<tag_contents>.*?)'.// Tag contents (multiline possible).
            '(?<tag_close>\<\/\\2\>)/uis'; // e.g. closing `</a>` tag.

        $tokenized['string'] = preg_replace_callback($a, function ($m) use (&$tokenized) {
            $tokenized['tokens'][] = $m[0]; // Tokenize.
            return '%#%token-'.$tokenized['marker'].'-'.(count($tokenized['tokens']) - 1).'%#%';
        }, $tokenized['string']); // Tags replaced by tokens.

        tags: // Target point; HTML `<a-z0-9>` tags.

        if (!in_array('tags', $tokenize, true)) {
            goto md_fences; // Not tokenizing these.
        }
        if (mb_stripos($tokenized['string'], '<') === false) {
            goto md_fences; // Nothing to tokenize here.
        }
        $tags = // This matches HTML `<a-z0-9>` tags (i.e., tags only).
            '/(?<tag_open_close_bracket>\<\/?)'.// Open or close `<[/]` bracket.
            '(?<tag_open_close_name>[a-z0-9]+)'.// See: <http://jas.xyz/1P1MQyh>
            '(?<tag_open_close_attrs_bracket>\>|\s[^>]*\>)/ui'; // Attributes & `>`.

        $tokenized['string'] = preg_replace_callback($tags, function ($m) use (&$tokenized) {
            $tokenized['tokens'][] = $m[0]; // Tokenize.
            return '%#%token-'.$tokenized['marker'].'-'.(count($tokenized['tokens']) - 1).'%#%';
        }, $tokenized['string']); // Tags replaced by tokens.

        md_fences: // Target point; Markdown pre/code fences.

        if (!in_array('md_fences', $tokenize, true)) {
            goto md_links; // Not tokenizing these.
        }
        if (mb_strpos($tokenized['string'], '~') === false && mb_strpos($tokenized['string'], '`') === false) {
            goto md_links; // Nothing to tokenize here.
        }
        $md_fences = // Markdown pre/code fences.
            '/(?<fence_open>~{3,}|`{3,}|`)'.// Opening fence.
            '(?<fence_contents>.*?)'.// Contents (multiline possible).
            '(?<fence_close>\\1)/uis'; // Closing fence; ~~~, ```, `.

        $tokenized['string'] = preg_replace_callback($md_fences, function ($m) use (&$tokenized) {
            $tokenized['tokens'][] = $m[0]; // Tokenize.
            return '%#%token-'.$tokenized['marker'].'-'.(count($tokenized['tokens']) - 1).'%#%';
        }, $tokenized['string']); // Fences replaced by tokens.

        md_links: // Target point; [Markdown](links).
        // This also tokenizes [Markdown]: <link> "definitions".
        // This routine includes considerations for images also.

        // NOTE: The tokenizer does NOT deal with links that reference definitions, as this is not necessary.
        //    So, while we DO tokenize <link> "definitions" themselves, the [actual][references] to
        //    these definitions do not need to be tokenized; i.e., it is not necessary here.

        if (!in_array('md_links', $tokenize, true)) {
            goto finale; // Not tokenizing these.
        }
        $tokenized['string'] = preg_replace_callback(
            array('/^[ ]*(?:\[[^\]]+\])+[ ]*\:[ ]*(?:\<[^>]+\>|\S+)(?:[ ]+.+)?$/um',
                    '/\!?\[(?:(?R)|[^\]]*)\]\([^)]+\)(?:\{[^}]*\})?/u', ),
            function ($m) use (&$tokenized) {
                $tokenized['tokens'][] = $m[0]; // Tokenize.
                return '%#%token-'.$tokenized['marker'].'-'.(count($tokenized['tokens']) - 1).'%#%';
            },
            $tokenized['string'] // Shortcodes replaced by tokens.
        );
        finale: // Target point; grand finale (return).

        return $tokenized; // Array w/ string, tokens, and marker.
    }

    /**
     * Restore tokens.
     *
     * @since 150424 Initial release.
     *
     * @param array $tokenized `string`, `tokens`, `marker`.
     *
     * @return string The `string` w/ restored tokens.
     */
    public function restore(array $tokenized): string
    {
        extract($tokenized); // Extract as local variables.

        if (!isset($string, $tokens, $marker)) {
            throw new Exception('Missing `string`, `tokens`, `marker`.');
        }
        if (!$tokens || !$marker || mb_strpos($string, '%#%') === false) {
            return $string; // Nothing to restore in this case.
        }
        foreach (array_reverse($tokens, true) as $_token => $_value) {
            // Must go in reverse order so nested tokens unfold properly.
            $string = str_replace('%#%token-'.$marker.'-'.$_token.'%#%', $_value, $string);
        } // unset($_token, $_value); // Housekeeping.

        return $string;
    }
}
