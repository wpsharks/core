<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * EOL utilities.
 *
 * @since 150424 Initial release.
 */
class Eols extends Classes\AbsBase
{
    /**
     * Normalizes end of line chars deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value Any input value.
     *
     * @return string|array|object With normalized end of line chars deeply.
     */
    public function normalize($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->normalize($_value);
            } // unset($_key, $_value);
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        $string = str_replace(array("\r\n", "\r"), "\n", $string);
        $string = preg_replace('/'."\n".'{3,}/u', "\n\n", $string);

        return $string;
    }
}
