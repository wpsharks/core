<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Multibyte `strrev()`.
 *
 * @since 150424 Enhancing multibyte support.
 */
class StrRev extends Classes\Core\Base\Core
{
    /**
     * Multibyte `strrev()`.
     *
     * @since 150424 Enhancing multibyte support.
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
        preg_match_all('/./us', $string, $m);

        return $string = implode('', array_reverse($m[0]));
    }
}
