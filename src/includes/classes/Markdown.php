<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use ParsedownExtra;
use Michelf\MarkdownExtra;

/**
 * Markdown utilities.
 *
 * @since 150424 Initial release.
 */
class Markdown extends AbsBase
{
    protected $Trim;
    protected $PhpHas;
    protected $HtmlSpcsm;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(
        Trim $Trim,
        PhpHas $PhpHas,
        HtmlSpcsm $HtmlSpcsm
    ) {
        parent::__construct();

        $this->Trim      = $Trim;
        $this->PhpHas    = $PhpHas;
        $this->HtmlSpcsm = $HtmlSpcsm;
    }

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
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        if (!($string = $this->Trim((string) $value))) {
            return $string; // Not possible.
        }
        $default_args = [
            'flavor' => 'markdown-extra',
            // `parsedown-extra` is faster, but buggy.
            // See: <https://github.com/erusev/parsedown-extra/issues/44>
            'oembed' => false,
            'breaks' => true,
            'no_p'   => false,
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        $flavor = (string) $args['flavor'];
        $oembed = (boolean) $args['oembed'];
        $breaks = (boolean) $args['breaks'];
        $no_p   = (boolean) $args['no_p'];

        if ($oembed && mb_strpos($string, '://') !== false
                && $this->PhpHas->callableFunction('wp_embed_defaults')
                && $this->PhpHas->callableFunction('wp_oembed_get')) {
            $_spcsm           = $this->HtmlSpcsm->tokenize($string);
            $_oembed_args     = array_merge(wp_embed_defaults(), ['discover' => false]);
            $_spcsm['string'] = preg_replace_callback('/^\s*(https?:\/\/[^\s"]+)\s*$/uim', function ($m) use ($_oembed_args) {
                $oembed = wp_oembed_get($m[1], $_oembed_args);
                return $oembed ? $oembed : $m[0];
            }, $_spcsm['string']);
            $string = $this->HtmlSpcsm->restore($_spcsm);
            unset($_spcsm, $_oembed_args); // Housekeeping.
        }
        if ($flavor === 'parsedown-extra') {
            if (is_null($ParsedownExtra = &$this->staticKey(__FUNCTION__, $flavor))) {
                $ParsedownExtra = new ParsedownExtra();
            }
            $ParsedownExtra->setBreaksEnabled($breaks);
            $html = $ParsedownExtra->text($string);
        } else {
            $flavor = 'markdown-extra'; // Default flavor.
            if (is_null($MarkdownExtra = &$this->staticKey(__FUNCTION__, $flavor))) {
                $MarkdownExtra                    = new MarkdownExtra();
                $MarkdownExtra->code_class_prefix = 'language-';
            }
            $html = $MarkdownExtra->transform($string);
        }
        if ($no_p) {
            $html = preg_replace('/^\<p\>/ui', '', $html);
            $html = preg_replace('/\<\/p\>$/ui', '', $html);
        }
        return $html;
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
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        if (!($string = $this->Trim((string) $value))) {
            return $string; // Not possible.
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
