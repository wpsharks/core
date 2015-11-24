<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Text conversion utilities.
 *
 * @since 151121 Text conversions.
 */
class TextConvert extends Classes\AbsBase
{
    /**
     * Text to HTML markup.
     *
     * @since 151121 Text conversion.
     *
     * @param mixed $value Any input value.
     * @param array $args  Any additional behavioral args.
     *
     * @return string|array|object Text value(s).
     */
    public function toHtml($value, array $args = [])
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->toHtml($_value, $args);
            } // unset($_key, $_value); // Housekeeping.
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        $default_args = []; // None at this time.
        $args         = array_merge($default_args, $args);
        $args         = array_intersect_key($args, $default_args);

        $string = $this->Utils->HtmlEntities->encode($string);
        $string = nl2br($this->Utils->Eols->normalize($string));
        $string = $this->Utils->HtmlConvert->urlsToAnchors($string);
        $string = $this->Utils->HtmlTrim($this->Utils->HtmlWhitespace->normalize($string));

        return $string;
    }
}
