<?php
namespace WebSharks\Core\Traits;

/**
 * HTML-To-Text utilities.
 *
 * @since 150424 Initial release.
 */
trait HtmlToUtils
{
    abstract protected function eolsN($value);
    abstract protected function htmlIs($string);
    abstract protected function htmlNWhitespace($value);
    abstract protected function htmlEscUn($value, $flags = null);
    abstract protected function regexQuote($value, $delimiter = '/');
    abstract protected function htmlStripAttrs($value, array $args = array());
    abstract protected function htmlTrim($value, $chars = '', $extra_chars = '', $side = '');
    abstract protected function htmlTokenizeSpcsm($string, array $tokenize_only = array(), $marker = '');
    abstract protected function htmlTokensRestoreSpcsm(array $spcsm);
    abstract protected function htmlBalanceTags($value);

    /**
     * Convert HTML markup converted to plain text.
     *
     * @since 150424 Initial release.
     *
     * @param string $value Any input value.
     * @param array  $args  Any additional behavioral args.
     *
     * @return string HTML markup converted to plain text.
     */
    protected function htmlToText($value, array $args = array())
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->htmlToText($_value, $args);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        if (!($string = trim((string) $value))) {
            return $string; // Not possible.
        }
        $default_args = [
            'br2nl'                 => true,
            'strip_content_in_tags' => $this->def_html_invisible_tags,
            'inject_eol_after_tags' => $this->def_html_block_tags,
        ];
        $args         = array_merge($default_args, $args);
        $args         = array_intersect_key($args, $default_args);

        $br2nl = (boolean) $args['br2nl']; // Allow line breaks?

        $strip_content_in_tags            = (array) $args['strip_content_in_tags'];
        $strip_content_in_tags_regex_frag = implode('|', $this->regexQuote($strip_content_in_tags));

        $inject_eol_after_tags            = (array) $args['inject_eol_after_tags'];
        $inject_eol_after_tags_regex_frag = implode('|', $this->regexQuote($inject_eol_after_tags));

        $string = preg_replace('/\<('.$strip_content_in_tags_regex_frag.')(?:\>|\s[^>]*\>).*?\<\/\\1\>/is', '', $string);
        $string = preg_replace('/\<\/(?:'.$inject_eol_after_tags_regex_frag.')\>/i', '${0}'."\n", $string);
        $string = preg_replace('/\<(?:'.$inject_eol_after_tags_regex_frag.')(?:\/\s*\>|\s[^\/>]*\/\s*\>)/i', '${0}'."\n", $string);

        $string = strip_tags($string, $br2nl ? '<br>' : '');
        $string = $this->htmlEscUn($string);
        $string = str_replace("\xC2\xA0", ' ', $string);

        if ($br2nl) {
            $string = preg_replace('/\<br(?:\>|\/\s*\>|\s[^\/>]*\/\s*\>)/', "\n", $string);
            $string = $this->eolsN($string); // Normalize line breaks.
            $string = preg_replace('/[ '."\t\x0B".']+/', ' ', $string);
        } else {
            $string = preg_replace('/\s+/', ' ', $string);
        }
        $string = trim($string); // Trim up.

        return $string;
    }

    /**
     * Convert HTML to rich text; w/ allowed tags only.
     *
     * @since 150424 Initial release.
     *
     * @param string $value Any input value.
     * @param array  $args  Any additional behavioral args.
     *
     * @return string HTML to rich text; w/ allowed tags only.
     */
    protected function htmlToRichText($value, array $args = array())
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->htmlToRichText($_value, $args);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        if (!($string = trim((string) $value))) {
            return $string; // Not possible.
        }
        $default_args = [
            'br2nl'                 => true,

            'allowed_tags'          => [
                'a',
                'strong', 'b',
                'i', 'em',
                'ul', 'ol', 'li',
                'code', 'pre',
                'q', 'blockquote',
            ],
            'allowed_attributes'    => [
                'href',
            ],

            'strip_content_in_tags' => $this->def_html_invisible_tags,
            'inject_eol_after_tags' => $this->def_html_block_tags,
        ];
        $args         = array_merge($default_args, $args);
        $args         = array_intersect_key($args, $default_args);

        $br2nl = (boolean) $args['br2nl']; // Allow line breaks?

        $allowed_tags = (array) $args['allowed_tags'];
        if ($br2nl) {
            $allowed_tags[] = 'br'; // Allow `<br>` in this case.
        }
        $allowed_tags       = array_unique(array_map('strtolower', $allowed_tags));
        $allowed_attributes = (array) $args['allowed_attributes'];

        $strip_content_in_tags            = (array) $args['strip_content_in_tags'];
        $strip_content_in_tags            = array_map('strtolower', $strip_content_in_tags);
        $strip_content_in_tags            = array_diff($strip_content_in_tags, $allowed_tags);
        $strip_content_in_tags_regex_frag = implode('|', $this->regexQuote($strip_content_in_tags));

        $inject_eol_after_tags            = (array) $args['inject_eol_after_tags'];
        $inject_eol_after_tags            = array_map('strtolower', $inject_eol_after_tags);
        $inject_eol_after_tags_regex_frag = implode('|', $this->regexQuote($inject_eol_after_tags));

        $string = preg_replace('/\<('.$strip_content_in_tags_regex_frag.')(?:\>|\s[^>]*\>).*?\<\/\\1\>/is', '', $string);
        $string = preg_replace('/\<\/(?:'.$inject_eol_after_tags_regex_frag.')\>/i', '${0}'."\n", $string);
        $string = preg_replace('/\<(?:'.$inject_eol_after_tags_regex_frag.')(?:\/\s*\>|\s[^\/>]*\/\s*\>)/i', '${0}'."\n", $string);

        $string = strip_tags($string, $allowed_tags ? '<'.implode('><', $allowed_tags).'>' : '');
        $string = $this->htmlStripAttrs($string, compact('allowed_attributes'));
        $string = $this->htmlBalanceTags($string); // Force balanced tags.

        $spcsm  = $this->htmlTokenizeSpcsm($string);
        $string = &$spcsm['string'];

        if ($br2nl) {
            $string = preg_replace('/\<br(?:\>|\/\s*\>|\s[^\/>]*\/\s*\>)/', "\n", $string);
            $string = $this->eolsN($string); // Normalize line breaks.
            $string = preg_replace('/[ '."\t\x0B".']+/', ' ', $string);
        } else {
            $string = preg_replace('/\s+/', ' ', $string);
        }
        $string = $this->htmlNWhitespace($string);
        $string = $this->htmlTokensRestoreSpcsm($spcsm);
        $string = $this->htmlTrim($string);

        return $string;
    }

    /**
     * Converts HTML into structured text.
     *
     * @param string $value Any input value.
     * @param string $to    The Pandoc-compatible format.
     *
     * @return string The input HTML converted to structured text.
     */
    protected function htmlToPandoc($value, $to)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->htmlToPandoc($_value, $to);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        if (!($html = trim((string) $value))) {
            return $html; // Nothing to do.
        }
        if (!$this->htmlIs($html)) {
            return $html; // Not HTML markup.
        }
        if (!($to = trim((string) $to))) {
            return $html; // Nothing to do.
        }
        try {
            $pandoc         = new \Pandoc\Pandoc();
            $pandoc_options = array(
                'from'          => 'html',
                'to'            => $to,
                'parse-raw'     => null,
                'atx-headers'   => null,
                'no-wrap'       => null,
                'preserve-tabs' => null,
            );
            return $pandoc->runWith($html, $pandoc_options);
        } catch (\Exception $exception) {
            return ''; // Failure.
        }
    }
}
