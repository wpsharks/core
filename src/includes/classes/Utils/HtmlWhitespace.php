<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * HTML whitespace utilities.
 *
 * @since 150424 Initial release.
 */
class HtmlWhitespace extends Classes\AppBase implements Interfaces\HtmlConstants
{
    /**
     * Normalizes HTML whitespace deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value Any input value.
     *
     * @return string|array|object With normalized HTML whitespace deeply.
     */
    public function normalize($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->normalize($_value);
            } // unset($_key, $_value);
            return c\normalize_eols($value);
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        if (is_null($whitespace = &$this->cacheKey(__FUNCTION__.'_whitespace'))) {
            $whitespace = implode('|', array_keys($this::HTML_WHITESPACE));
        }
        $string = preg_replace('/('.$whitespace.')('.$whitespace.')('.$whitespace.')+/u', '${1}${2}', $string);

        return c\normalize_eols($string);
    }
}
