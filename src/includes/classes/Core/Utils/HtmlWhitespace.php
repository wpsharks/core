<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * HTML whitespace utilities.
 *
 * @since 150424 Initial release.
 */
class HtmlWhitespace extends Classes\Core\Base\Core implements Interfaces\HtmlConstants
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
            return $this->c::normalizeEols($value);
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        if (is_null($whitespace = &$this->cacheKey(__FUNCTION__.'_whitespace'))) {
            $whitespace = implode('|', array_keys($this::HTML_WHITESPACE));
        }
        $string = preg_replace('/('.$whitespace.')('.$whitespace.')('.$whitespace.')+/u', '${1}${2}', $string);

        return $this->c::normalizeEols($string);
    }
}
