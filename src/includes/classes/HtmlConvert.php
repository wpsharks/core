<?php
namespace WebSharks\Core\Classes;

use WebSharks\Core\Traits;

/**
 * HTML-To-Text utilities.
 *
 * @since 150424 Initial release.
 */
class HtmlConvert extends AbsBase
{
    use Traits\HtmlDefinitions;

    protected $Eols;
    protected $Html;
    protected $HtmlTrim;
    protected $HtmlStrip;
    protected $HtmlSpcsm;
    protected $RegexQuote;
    protected $HtmlBalance;
    protected $HtmlEntities;
    protected $HtmlWhitespace;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(
        Eols $Eols,
        Html $Html,
        HtmlTrim $HtmlTrim,
        HtmlStrip $HtmlStrip,
        HtmlSpcsm $HtmlSpcsm,
        RegexQuote $RegexQuote,
        HtmlBalance $HtmlBalance,
        HtmlEntities $HtmlEntities,
        HtmlWhitespace $HtmlWhitespace
    ) {
        parent::__construct();

        $this->Eols           = $Eols;
        $this->Html           = $Html;
        $this->HtmlTrim       = $HtmlTrim;
        $this->HtmlStrip      = $HtmlStrip;
        $this->HtmlSpcsm      = $HtmlSpcsm;
        $this->RegexQuote     = $RegexQuote;
        $this->HtmlBalance    = $HtmlBalance;
        $this->HtmlEntities   = $HtmlEntities;
        $this->HtmlWhitespace = $HtmlWhitespace;
    }

    /**
     * Convert HTML markup converted to plain text.
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
        if (!($string = trim((string) $value))) {
            return $string; // Not possible.
        }
        $default_args = [
            'br2nl'                 => true,
            'strip_content_in_tags' => $this->DEF_HTML_INVISIBLE_TAGS,
            'inject_eol_after_tags' => $this->DEF_HTML_BLOCK_TAGS,
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        $br2nl = (boolean) $args['br2nl']; // Allow line breaks?

        $strip_content_in_tags            = (array) $args['strip_content_in_tags'];
        $strip_content_in_tags_regex_frag = implode('|', $this->RegexQuote($strip_content_in_tags));

        $inject_eol_after_tags            = (array) $args['inject_eol_after_tags'];
        $inject_eol_after_tags_regex_frag = implode('|', $this->RegexQuote($inject_eol_after_tags));

        $string = preg_replace('/\<('.$strip_content_in_tags_regex_frag.')(?:\>|\s[^>]*\>).*?\<\/\\1\>/is', '', $string);
        $string = preg_replace('/\<\/(?:'.$inject_eol_after_tags_regex_frag.')\>/i', '${0}'."\n", $string);
        $string = preg_replace('/\<(?:'.$inject_eol_after_tags_regex_frag.')(?:\/\s*\>|\s[^\/>]*\/\s*\>)/i', '${0}'."\n", $string);

        $string = strip_tags($string, $br2nl ? '<br>' : '');
        $string = $this->HtmlEntities->decode($string);
        $string = str_replace("\xC2\xA0", ' ', $string);

        if ($br2nl) {
            $string = preg_replace('/\<br(?:\>|\/\s*\>|\s[^\/>]*\/\s*\>)/', "\n", $string);
            $string = $this->Eols->normalize($string); // Normalize line breaks.
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
        if (!($string = trim((string) $value))) {
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

            'strip_content_in_tags' => $this->DEF_HTML_INVISIBLE_TAGS,
            'inject_eol_after_tags' => $this->DEF_HTML_BLOCK_TAGS,
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

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
        $strip_content_in_tags_regex_frag = implode('|', $this->RegexQuote($strip_content_in_tags));

        $inject_eol_after_tags            = (array) $args['inject_eol_after_tags'];
        $inject_eol_after_tags            = array_map('strtolower', $inject_eol_after_tags);
        $inject_eol_after_tags_regex_frag = implode('|', $this->RegexQuote($inject_eol_after_tags));

        $string = preg_replace('/\<('.$strip_content_in_tags_regex_frag.')(?:\>|\s[^>]*\>).*?\<\/\\1\>/is', '', $string);
        $string = preg_replace('/\<\/(?:'.$inject_eol_after_tags_regex_frag.')\>/i', '${0}'."\n", $string);
        $string = preg_replace('/\<(?:'.$inject_eol_after_tags_regex_frag.')(?:\/\s*\>|\s[^\/>]*\/\s*\>)/i', '${0}'."\n", $string);

        $string = strip_tags($string, $allowed_tags ? '<'.implode('><', $allowed_tags).'>' : '');
        $string = $this->HtmlStrip->attributes($string, compact('allowed_attributes'));
        $string = $this->HtmlBalance->tags($string); // Force balanced tags.

        $spcsm  = $this->HtmlSpcsm->tokenize($string);
        $string = &$spcsm['string'];

        if ($br2nl) {
            $string = preg_replace('/\<br(?:\>|\/\s*\>|\s[^\/>]*\/\s*\>)/', "\n", $string);
            $string = $this->Eols->normalize($string); // Normalize line breaks.
            $string = preg_replace('/[ '."\t\x0B".']+/', ' ', $string);
        } else {
            $string = preg_replace('/\s+/', ' ', $string);
        }
        $string = $this->HtmlWhitespace->normalize($string);
        $string = $this->HtmlSpcsm->restore($spcsm);
        $string = $this->HtmlTrim($string);

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
    public function toPandoc($value, $to)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->toPandoc($_value, $to);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        if (!($html = trim((string) $value))) {
            return $html; // Nothing to do.
        }
        if (!$this->Html->is($html)) {
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
