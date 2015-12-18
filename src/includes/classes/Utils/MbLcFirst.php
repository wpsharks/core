<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Multibyte `lcfirst()`.
 *
 * @since 15xxxx Enhancing multibyte support.
 */
class MbLcFirst extends Classes\AppBase
{
    /**
     * Multibyte `lcfirst()`.
     *
     * @since 15xxxx Enhancing multibyte support.
     *
     * @param mixed $value Any input value.
     *
     * @return string|array|object Output value.
     */
    public function __invoke($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($_value);
            } // unset($_key, $_value);
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        $first     = mb_substr($string, 0, 1);
        $remaining = mb_substr($string, 1);

        return mb_strtolower($first).$remaining;
    }
}
