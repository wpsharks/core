<?php
/**
 * Array sort utilities.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Array sort utilities.
 *
 * @since 150424 Initial release.
 */
class Sort extends Classes\Core\Base\Core
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
