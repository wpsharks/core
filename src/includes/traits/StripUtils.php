<?php
namespace WebSharks\Core\Traits;

/**
 * Strip Utilities.
 *
 * @since 150424 Initial release.
 */
trait StripUtils
{
    /**
     * Strips slashes from a string value.
     *
     * @param string $string A string value.
     *
     * @return string Stripped string.
     */
    protected function strip($string)
    {
        return $this->stripDeep((string) $string);
    }

    /**
     * Strips slashes deeply.
     *
     * @param mixed $value Any value can be converted into a stripped string.
     *                     Actually, objects can't, but this recurses into objects.
     *
     * @return string|array|object Stripped string, array, object.
     */
    protected function stripDeep($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->stripDeep($_value);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        return stripslashes((string) $value);
    }
}
