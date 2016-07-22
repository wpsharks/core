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
     * @param mixed $value    Any input value.
     * @param bool  $compress Compress `\n{3,}` into 2?
     *
     * @return string|array|object With normalized HTML whitespace deeply.
     */
    public function normalize($value, bool $compress = false)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->normalize($_value, $compress);
            } // unset($_key, $_value);
            return $this->c::normalizeEols($value, $compress);
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        if (($whitespace = &$this->cacheKey(__FUNCTION__.'_whitespace')) === null) {
            $whitespace = implode('|', $this::HTML_WHITESPACE);
        }
        if ($compress) { // NOTE: This is the only task at the moment. It could change in the future.
            $string = preg_replace('/('.$whitespace.')('.$whitespace.')('.$whitespace.')+/u', '${1}${2}', $string);
        }
        return $this->c::normalizeEols($string, $compress);
    }
}
