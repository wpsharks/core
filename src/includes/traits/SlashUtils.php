<?php
namespace WebSharks\Core\Traits;

/**
 * Strip Utilities.
 *
 * @since 150424 Initial release.
 */
trait SlashUtils
{
    /**
     * Adds slashes to a string value.
     *
     * @param string $string A string value.
     *
     * @return string Slashed string.
     */
    protected function slash($string)
    {
        return $this->slashDeep((string) $string);
    }

    /**
     * Adds slashes deeply.
     *
     * @param mixed $value Any value can be converted into a slashed string.
     *                     Actually, objects can't, but this recurses into objects.
     *
     * @return string|array|object Slashed string, array, object.
     */
    protected function slashDeep($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->slashDeep($_value);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        return addslashes((string) $value);
    }
}
