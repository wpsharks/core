<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Array sort utilities.
 *
 * @since 150424 Initial release.
 */
class ArraySort extends Classes\AbsBase
{
    /**
     * Sorts an array deeply by its keys.
     *
     * @since 15xxxx Adding array utils.
     *
     * @param array $array Input array to sort.
     * @param int   $flags Optional; defaults to `SORT_REGULAR`.
     *
     * @return array Array sorted deeply by its keys.
     */
    public function byKey(array $array, int $flags = SORT_REGULAR): array
    {
        ksort($array, $flags);

        foreach ($array as $_key => &$_value) {
            if (is_array($_value)) {
                $_value = $this->byKey($_value, $flags);
            }
        } // unset($_key, $_value); // Housekeeping.

        return $array;
    }
}
