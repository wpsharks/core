<?php
namespace WebSharks\Core\Traits;

/**
 * Dimension Utilities.
 *
 * @since 150424 Initial release.
 */
trait DimensionUtils
{
    /**
     * Forces an array to a single dimension.
     *
     * @param array $array Input array.
     *
     * @return array Output array, with only ONE dimension.
     */
    public function oneDimension(array $array)
    {
        foreach ($array as $_key => $_value) {
            if (is_array($_value) || is_object($_value)) {
                unset($array[$_key]);
            }
        }
        unset($_key, $_value); // Housekeeping.

        return $array;
    }
}
