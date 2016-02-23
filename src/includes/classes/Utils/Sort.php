<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Functions\__;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Array sort utilities.
 *
 * @since 150424 Initial release.
 */
class Sort extends Classes\AppBase
{
    /**
     * Sorts by key.
     *
     * @since 150424 Array utils.
     *
     * @param array $array Input array.
     * @param int   $flags Defaults to `SORT_REGULAR`.
     *
     * @return array Output array.
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
