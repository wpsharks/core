<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Regex frag utilities.
 *
 * @since 160104 Regex fragments.
 */
class RegexFrag extends Classes\Core
{
    /**
     * Convert patterns to frags.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value Any input value.
     *
     * @return string|array|object Fragment(s).
     */
    public function __invoke($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($_value, $delimiter);
            } // unset($_key, $_value);
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        return mb_substr($string, 2, -3); // i.e., `/^` and `$/u` stripped away.
    }
}
