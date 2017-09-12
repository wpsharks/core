<?php
/**
 * Change recursive.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Change recursive.
 *
 * @since 17xxxx Swap recursive.
 */
class ArrayChangeRecursive extends Classes\Core\Base\Core
{
    /**
     * Change recursive.
     *
     * @since 17xxxx Change recursive.
     *
     * @param array $array    Base array.
     * @param array ...$merge Array to merge.
     *
     * @note The same as `array_replace_recursive()`, except numerically-indexed arrays
     * are emptied out entirely before, and only if, they are going to be replaced by a merge.
     */
    public function __invoke(array $array, array ...$merge): array
    {
        $array        = $this->maybeEmptyNumericArrays($array, ...$merge);
        return $array = array_replace_recursive($array, ...$merge);
    }

    /**
     * Empty numeric arrays.
     *
     * @since 17xxxx Change recursive.
     *
     * @param array $array    Base array.
     * @param array ...$merge Array to merge.
     *
     * @return array w/ possibly-empty numeric arrays.
     */
    protected function maybeEmptyNumericArrays(array $array, array ...$merge): array
    {
        if (!$merge) { // Save time. Merge is empty?
            return $base; // Nothing to do here.
        }
        foreach ($array as $_key => &$_value) {
            if (is_array($_value)) {
                foreach ($merge as $_merge) {
                    if (array_key_exists($_key, $_merge)) {
                        if (!$_value || $_value === array_values($_value)) {
                            $_value = []; // Empty.
                        } elseif (is_array($_merge[$_key])) {
                            $_value = $this->maybeEmptyNumericArrays($_value, $_merge[$_key]);
                        }
                    }
                } // unset($_merge); // Housekeeping.
            }
        } // unset($_key, $_value); // Housekeeping.

        return $array;
    }
}
