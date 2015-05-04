<?php
namespace WebSharks\Core\Traits;

/**
 * Array sort utilities.
 *
 * @since 150424 Initial release.
 */
trait ArraySortUtils
{
    /**
     * Sorts an array deeply by its keys.
     *
     * @param array $array Input array to sort.
     * @param int   $flags Optional; defaults to `SORT_REGULAR`.
     *
     * @return array Array sorted deeply by its keys.
     */
    protected function arraySortByKey(array $array, $flags = SORT_REGULAR)
    {
        ksort($array, $flags);

        foreach ($array as $_key => &$_value) {
            if (is_array($_value)) {
                $_value = $this->arraySortByKey($_value, $flags);
            }
        }
        unset($_key, $_value); // Housekeeping.

        return $array;
    }
}
