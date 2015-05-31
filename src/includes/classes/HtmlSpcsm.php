<?php
namespace WebSharks\Core\Classes;

/**
 * HTML SPCSM utilities.
 *
 * @since 150424 Initial release.
 */
class HtmlSpcsm extends AbsBase
{
    protected $PhpHas;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(
        PhpHas $PhpHas
    ) {
        parent::__construct();

        $this->PhpHas = $PhpHas;
    }

    /**
     * Shortcode/pre/code/samp/MD tokens.
     *
     * @since 150424 Initial release.
     *
     * @param string $string        Input string to tokenize.
     * @param array  $tokenize_only Can be used to limit what is tokenized.
     * @param string $marker        Optional marker suffix.
     *
     * @return array Array with: `string`, `tokens`, `marker`.
     */
    public function tokenize($string, array $tokenize_only = array(), $marker = '')
    {
        $marker = str_replace('.', '', uniqid('', true)).($marker ? sha1($marker) : '');

        if (!($string = trim((string) $string))) {
            return ['string' => $string, 'tokens' => array(), 'marker' => $marker];
        }
        $spcsm = ['string' => $string, 'tokens' => array(), 'marker' => $marker];

        shortcodes: // Target point; `[shortcode][/shortcode]`.

        if ($tokenize_only && !in_array('shortcodes', $tokenize_only, true)) {
            goto pre; // Not tokenizing these.
        }
        if (empty($GLOBALS['shortcode_tags']) || strpos($spcsm['string'], '[') === false
            || !$this->PhpHas->callableFunction('get_shortcode_regex')) {
            goto pre; // No `[` shortcodes.
        }
        $spcsm['string'] = preg_replace_callback('/'.get_shortcode_regex().'/s', function ($m) use (&$spcsm) {
            $spcsm['tokens'][] = $m[0]; // Tokenize.
            return '%#%spcsm-'.$spcsm['marker'].'-'.(count($spcsm['tokens']) - 1).'%#%';
        }, $spcsm['string']); // Shortcodes replaced by tokens.

        pre: // Target point; HTML `<pre>` tags.

        if ($tokenize_only && !in_array('pre', $tokenize_only, true)) {
            goto code; // Not tokenizing these.
        }
        if (stripos($spcsm['string'], '<pre') === false) {
            goto code; // Nothing to tokenize here.
        }
        $pre = // HTML `<pre>` tags.
            '/(?P<tag_open_bracket>\<)'.// Opening `<` bracket.
            '(?P<tag_open_name>pre)'.// Tag name; e.g. a `pre` tag.
            '(?P<tag_open_attrs_bracket>\>|\s+[^>]*\>)'.// Attributes & `>`.
            '(?P<tag_contents>.*?)'.// Tag contents (multiline possible).
            '(?P<tag_close>\<\/\\2\>)/is'; // e.g. closing `</pre>` tag.

        $spcsm['string'] = preg_replace_callback($pre, function ($m) use (&$spcsm) {
            $spcsm['tokens'][] = $m[0]; // Tokenize.
            return '%#%spcsm-'.$spcsm['marker'].'-'.(count($spcsm['tokens']) - 1).'%#%';
        }, $spcsm['string']); // Tags replaced by tokens.

        code: // Target point; HTML `<code>` tags.

        if ($tokenize_only && !in_array('code', $tokenize_only, true)) {
            goto samp; // Not tokenizing these.
        }
        if (stripos($spcsm['string'], '<code') === false) {
            goto samp; // Nothing to tokenize here.
        }
        $code = // HTML `<code>` tags.
            '/(?P<tag_open_bracket>\<)'.// Opening `<` bracket.
            '(?P<tag_open_name>code)'.// Tag name; e.g. a `code` tag.
            '(?P<tag_open_attrs_bracket>\>|\s+[^>]*\>)'.// Attributes & `>`.
            '(?P<tag_contents>.*?)'.// Tag contents (multiline possible).
            '(?P<tag_close>\<\/\\2\>)/is'; // e.g. closing `</code>` tag.

        $spcsm['string'] = preg_replace_callback($code, function ($m) use (&$spcsm) {
            $spcsm['tokens'][] = $m[0]; // Tokenize.
            return '%#%spcsm-'.$spcsm['marker'].'-'.(count($spcsm['tokens']) - 1).'%#%';
        }, $spcsm['string']); // Tags replaced by tokens.

        samp: // Target point; HTML `<samp>` tags.

        if ($tokenize_only && !in_array('samp', $tokenize_only, true)) {
            goto md_fences; // Not tokenizing these.
        }
        if (stripos($spcsm['string'], '<samp') === false) {
            goto md_fences; // Nothing to tokenize here.
        }
        $samp = // HTML `<samp>` tags.
            '/(?P<tag_open_bracket>\<)'.// Opening `<` bracket.
            '(?P<tag_open_name>samp)'.// Tag name; e.g. a `samp` tag.
            '(?P<tag_open_attrs_bracket>\>|\s+[^>]*\>)'.// Attributes & `>`.
            '(?P<tag_contents>.*?)'.// Tag contents (multiline possible).
            '(?P<tag_close>\<\/\\2\>)/is'; // e.g. closing `</samp>` tag.

        $spcsm['string'] = preg_replace_callback($samp, function ($m) use (&$spcsm) {
            $spcsm['tokens'][] = $m[0]; // Tokenize.
            return '%#%spcsm-'.$spcsm['marker'].'-'.(count($spcsm['tokens']) - 1).'%#%';
        }, $spcsm['string']); // Tags replaced by tokens.

        md_fences: // Target point; Markdown pre/code fences.

        if ($tokenize_only && !in_array('md_fences', $tokenize_only, true)) {
            goto md_links; // Not tokenizing these.
        }
        if (strpos($spcsm['string'], '~') === false && strpos($spcsm['string'], '`') === false) {
            goto md_links; // Nothing to tokenize here.
        }
        $md_fences = // Markdown pre/code fences.
            '/(?P<fence_open>~{3,}|`{3,}|`)'.// Opening fence.
            '(?P<fence_contents>.*?)'.// Contents (multiline possible).
            '(?P<fence_close>\\1)/is'; // Closing fence; ~~~, ```, `.

        $spcsm['string'] = preg_replace_callback($md_fences, function ($m) use (&$spcsm) {
            $spcsm['tokens'][] = $m[0]; // Tokenize.
            return '%#%spcsm-'.$spcsm['marker'].'-'.(count($spcsm['tokens']) - 1).'%#%';
        }, $spcsm['string']); // Fences replaced by tokens.

        md_links: // Target point; [Markdown](links).
        // This also tokenizes [Markdown]: <link> "definitions".
        // This routine includes considerations for images also.

        // NOTE: The tokenizer does NOT deal with links that reference definitions, as this is not necessary.
        //    So, while we DO tokenize <link> "definitions" themselves, the [actual][references] to
        //    these definitions do not need to be tokenized; i.e., it is not necessary here.

        if ($tokenize_only && !in_array('md_links', $tokenize_only, true)) {
            goto finale; // Not tokenizing these.
        }
        $spcsm['string'] = preg_replace_callback(
            array('/^[ ]*(?:\[[^\]]+\])+[ ]*\:[ ]*(?:\<[^>]+\>|\S+)(?:[ ]+.+)?$/m',
                    '/\!?\[(?:(?R)|[^\]]*)\]\([^)]+\)(?:\{[^}]*\})?/', ),
            function ($m) use (&$spcsm) {
                $spcsm['tokens'][] = $m[0]; // Tokenize.
                return '%#%spcsm-'.$spcsm['marker'].'-'.(count($spcsm['tokens']) - 1).'%#%';
            },
            $spcsm['string'] // Shortcodes replaced by tokens.
        );
        finale: // Target point; grand finale (return).

        return $spcsm; // Array w/ string, tokens, and marker.
    }

    /**
     * Shortcode/pre/code/samp/MD restoration.
     *
     * @since 150424 Initial release.
     *
     * @param array $spcsm `string`, `tokens`, `marker`.
     *
     * @return string The `string` w/ tokens restored now.
     */
    public function restore(array $spcsm)
    {
        if (!isset($spcsm['string'])) {
            return ''; // Not possible.
        }
        if (!($string = trim((string) $spcsm['string']))) {
            return $string; // Nothing to restore.
        }
        $tokens = isset($spcsm['tokens']) ? (array) $spcsm['tokens'] : array();
        $marker = isset($spcsm['marker']) ? (string) $spcsm['marker'] : '';

        if (!$tokens || !$marker || strpos($string, '%#%') === false) {
            return $string; // Nothing to restore in this case.
        }
        foreach (array_reverse($tokens, true) as $_token => $_value) {
            // Must go in reverse order so nested tokens unfold properly.
            $string = str_replace('%#%spcsm-'.$marker.'-'.$_token.'%#%', $_value, $string);
        }
        unset($_token, $_value); // Housekeeping.

        return $string;
    }
}
