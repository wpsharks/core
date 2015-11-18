<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;

/**
 * HTML-To-Text utilities.
 *
 * @since 150424 Initial release.
 */
class HtmlConvert extends Classes\AbsBase implements Interfaces\HtmlConstants
{
    /**
     * Convert HTML markup to plain text.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value Any input value.
     * @param array $args  Any additional behavioral args.
     *
     * @return string|array|object Text value(s).
     */
    public function toText($value, array $args = array())
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->toText($_value, $args);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        if (!($string = $this->Utils->Trim((string) $value))) {
            return $string; // Not possible.
        }
        $default_args = [
            'br2nl'                 => true,
            'strip_content_in_tags' => $this::HTML_INVISIBLE_TAGS,
            'inject_eol_after_tags' => $this::HTML_BLOCK_TAGS,
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        $br2nl = (boolean) $args['br2nl']; // Allow line breaks?

        $strip_content_in_tags            = (array) $args['strip_content_in_tags'];
        $strip_content_in_tags_regex_frag = implode('|', $this->Utils->RegexQuote($strip_content_in_tags));

        $inject_eol_after_tags            = (array) $args['inject_eol_after_tags'];
        $inject_eol_after_tags_regex_frag = implode('|', $this->Utils->RegexQuote($inject_eol_after_tags));

        $string = preg_replace('/\<('.$strip_content_in_tags_regex_frag.')(?:\>|\s[^>]*\>).*?\<\/\\1\>/uis', '', $string);
        $string = preg_replace('/\<\/(?:'.$inject_eol_after_tags_regex_frag.')\>/ui', '${0}'."\n", $string);
        $string = preg_replace('/\<(?:'.$inject_eol_after_tags_regex_frag.')(?:\/\s*\>|\s[^\/>]*\/\s*\>)/ui', '${0}'."\n", $string);

        $string = strip_tags($string, $br2nl ? '<br>' : '');
        $string = $this->Utils->HtmlEntities->decode($string);
        $string = str_replace("\xC2\xA0", ' ', $string);

        if ($br2nl) {
            $string = preg_replace('/\<br(?:\>|\/\s*\>|\s[^\/>]*\/\s*\>)/u', "\n", $string);
            $string = $this->Utils->Eols->normalize($string); // Normalize line breaks.
            $string = preg_replace('/[ '."\t\x0B".']+/u', ' ', $string);
        } else {
            $string = preg_replace('/\s+/u', ' ', $string);
        }
        $string = $this->Utils->Trim($string); // Trim up.

        return $string;
    }

    /**
     * Convert HTML to rich text; w/ allowed tags only.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value Any input value.
     * @param array $args  Any additional behavioral args.
     *
     * @return string|array|object Rich text value(s).
     */
    public function toRichText($value, array $args = array())
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->toRichText($_value, $args);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        if (!($string = $this->Utils->Trim((string) $value))) {
            return $string; // Not possible.
        }
        $default_args = [
            'br2nl' => true,

            'allowed_tags' => [
                'a',
                'strong', 'b',
                'i', 'em',
                'ul', 'ol', 'li',
                'code', 'pre',
                'q', 'blockquote',
            ],
            'allowed_attributes' => [
                'href',
            ],

            'strip_content_in_tags' => $this::HTML_INVISIBLE_TAGS,
            'inject_eol_after_tags' => $this::HTML_BLOCK_TAGS,
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        $br2nl = (boolean) $args['br2nl']; // Allow line breaks?

        $allowed_tags = (array) $args['allowed_tags'];
        if ($br2nl) {
            $allowed_tags[] = 'br'; // Allow `<br>` in this case.
        }
        $allowed_tags       = array_unique(array_map('mb_strtolower', $allowed_tags));
        $allowed_attributes = (array) $args['allowed_attributes'];

        $strip_content_in_tags            = (array) $args['strip_content_in_tags'];
        $strip_content_in_tags            = array_map('mb_strtolower', $strip_content_in_tags);
        $strip_content_in_tags            = array_diff($strip_content_in_tags, $allowed_tags);
        $strip_content_in_tags_regex_frag = implode('|', $this->Utils->RegexQuote($strip_content_in_tags));

        $inject_eol_after_tags            = (array) $args['inject_eol_after_tags'];
        $inject_eol_after_tags            = array_map('mb_strtolower', $inject_eol_after_tags);
        $inject_eol_after_tags_regex_frag = implode('|', $this->Utils->RegexQuote($inject_eol_after_tags));

        $string = preg_replace('/\<('.$strip_content_in_tags_regex_frag.')(?:\>|\s[^>]*\>).*?\<\/\\1\>/uis', '', $string);
        $string = preg_replace('/\<\/(?:'.$inject_eol_after_tags_regex_frag.')\>/ui', '${0}'."\n", $string);
        $string = preg_replace('/\<(?:'.$inject_eol_after_tags_regex_frag.')(?:\/\s*\>|\s[^\/>]*\/\s*\>)/ui', '${0}'."\n", $string);

        $string = strip_tags($string, $allowed_tags ? '<'.implode('><', $allowed_tags).'>' : '');
        $string = $this->Utils->HtmlStrip->attributes($string, compact('allowed_attributes'));
        $string = $this->Utils->HtmlBalance->tags($string); // Force balanced tags.

        $spcsm  = $this->Utils->HtmlSpcsm->tokenize($string);
        $string = &$spcsm['string'];

        if ($br2nl) {
            $string = preg_replace('/\<br(?:\>|\/\s*\>|\s[^\/>]*\/\s*\>)/u', "\n", $string);
            $string = $this->Utils->Eols->normalize($string); // Normalize line breaks.
            $string = preg_replace('/[ '."\t\x0B".']+/u', ' ', $string);
        } else {
            $string = preg_replace('/\s+/u', ' ', $string);
        }
        $string = $this->Utils->HtmlWhitespace->normalize($string);
        $string = $this->Utils->HtmlSpcsm->restore($spcsm);
        $string = $this->Utils->HtmlTrim($string);

        return $string;
    }

    /**
     * Converts HTML into structured text.
     *
     * @param mixed  $value Any input value.
     * @param string $to    The Pandoc-compatible format.
     *
     * @return string|array|object Converted value(s).
     */
    public function toPandoc($value, string $to)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->toPandoc($_value, $to);
            } // unset($_key, $_value);

            return $value;
        }
        if (!($html = $this->Utils->Trim((string) $value))) {
            return $html; // Nothing to do.
        }
        if (!$this->Utils->Html->is($html)) {
            return $html; // Not HTML markup.
        }
        if (!($to = $this->Utils->Trim($to))) {
            return $html; // Nothing to do.
        }
        try {
            $Pandoc         = new \Pandoc\Pandoc();
            $pandoc_options = array(
                'from'          => 'html',
                'to'            => $to,
                'parse-raw'     => null,
                'atx-headers'   => null,
                'no-wrap'       => null,
                'preserve-tabs' => null,
            );
            return $Pandoc->runWith($html, $pandoc_options);
        } catch (\Exception $Exception) {
            return ''; // Failure.
        }
    }
}
