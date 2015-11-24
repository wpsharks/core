<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Multibyte `ucfirst()`.
 *
 * @since 15xxxx Enhancing multibyte support.
 */
class UcFirst extends Classes\AbsBase
{
    /**
     * Multibyte `ucfirst()`.
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
        $string = (string) $value;

        if (!isset($string[0])) {
            return $string;
        }
        $first     = mb_substr($string, 0, 1);
        $remaining = mb_substr($string, 1);

        return mb_strtoupper($first).$remaining;
    }
}
