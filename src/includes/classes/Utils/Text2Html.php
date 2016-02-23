<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Text2Html utilities.
 *
 * @since 151121 Text conversions.
 */
class Text2Html extends Classes\Core
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
    public function __invoke($value, array $args = [])
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($_value, $args);
            } // unset($_key, $_value);
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        $default_args = []; // None at this time.
        $args         = array_merge($default_args, $args);
        $args         = array_intersect_key($args, $default_args);

        $string = c\esc_html($string);
        $string = nl2br(c\normalize_eols($string));
        $string = c\html_anchorize($string);
        $string = c\normalize_html_whitespace($string);
        $string = c\html_trim($string);

        return $string;
    }
}
