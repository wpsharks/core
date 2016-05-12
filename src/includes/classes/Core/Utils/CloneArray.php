<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Clone an array deeply.
 *
 * @since 160511 Array cloning.
 */
class CloneArray extends Classes\Core\Base\Core
{
    /**
     * Clone an array deeply.
     *
     * @since 160511 Array cloning.
     *
     * @param array $value Input array.
     *
     * @return array Output array.
     */
    public function __invoke(array $array)
    {
        $clone = []; // Initialize.

        foreach ($array as $_key => $_value) {
            if (is_array($_value)) {
                $clone[$_key] = $this->__invoke($_value);
            } elseif (is_object($_value)) {
                $clone[$_key] = clone $_value;
            } else {
                $clone[$_key] = $_value;
            }
        } // unset($_key, $_value); // Housekeeping.

        return $clone; // Copy w/o references.
    }
}
