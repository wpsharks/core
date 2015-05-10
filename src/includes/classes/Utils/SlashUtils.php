<?php
namespace WebSharks\Core\Traits;

/**
 * Strip utilities.
 *
 * @since 150424 Initial release.
 */
trait SlashUtils
{
    /**
     * Adds slashes deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value Any input value.
     *
     * @return string|array|object Slashed value.
     */
    protected function slash($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->slash($_value);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        $string = (string) $value;

        return addslashes($string);
    }
}
