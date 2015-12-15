<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Slashes.
 *
 * @since 150424 Initial release.
 */
class Slashes extends Classes\AbsBase
{
    /**
     * Add slashes deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value Any input value.
     *
     * @return string|array|object Slashed value.
     */
    public function add($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->add($_value);
            } // unset($_key, $_value);
            return $value;
        }
        $string = (string) $value;

        return addslashes($string);
    }

    /**
     * Remove slashes deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value Any input value.
     *
     * @return string|array|object Unslashed value.
     */
    public function remove($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->strip($_value);
            } // unset($_key, $_value);
            return $value;
        }
        $string = (string) $value;

        return stripslashes($string);
    }
}
