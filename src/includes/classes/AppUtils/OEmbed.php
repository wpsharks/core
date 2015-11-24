<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * OEmbed utilities. @TODO Remove WordPress dependency.
 *
 * @since 150424 Initial release.
 */
class OEmbed extends Classes\AbsBase
{
    /**
     * OEmbed parser.
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
            } //unset($_key, $_value); // Housekeeping.
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        if (!mb_strpos($string, '://') !== false) {
            return $string; // Nothing to do.
        }
        if (defined('WPINC') && $this->Utils->PhpHas->callableFunction('wp_oembed_get')
          && $this->Utils->PhpHas->callableFunction('wp_embed_defaults')) {
            // WordPress-specific oEmbed handler.
            $tokenize  = ['shortcodes', 'pre', 'code', 'samp', 'md_fences', 'md_links'];
            $tokenized = $this->Utils->HtmlTokenizer->tokenize($string, $tokenize);
            $string    = &$tokenized['string']; // Tokenized string by reference.

            $oembed_args = array_merge(wp_embed_defaults(), ['discover' => false]);
            $string      = preg_replace_callback('/^\s*(https?:\/\/[^\s"]+)\s*$/uim', function ($m) use ($oembed_args) {
                $oembed = wp_oembed_get($m[1], $oembed_args);
                return $oembed ? $oembed : $m[0];
            }, $string); // oEmbed :-)

            $string = $this->Utils->HtmlTokenizer->restore($tokenized);
        }
        return $string;
    }
}
