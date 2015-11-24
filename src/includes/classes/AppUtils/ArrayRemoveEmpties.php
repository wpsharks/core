<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Array (w/o empty values).
 *
 * @since 15xxxx Adding array utils.
 */
class ArrayRemoveEmpties extends Classes\AbsBase
{
    /**
     * Remove empty values.
     *
     * @since 15xxxx Adding array utils.
     *
     * @param array $array Input array to iterate.
     *
     * @return array Array w/o empty values.
     */
    public function __invoke(array $array): array
    {
        foreach ($array as $_key => &$_value) {
            if (empty($_value)) {
                unset($array[$_key]);
            } elseif (is_array($_value)) {
                if (empty($_value = $this->__invoke($_value))) {
                    unset($array[$_key]);
                }
            }
        } // unset($_key, $_value); // Housekeeping.

        return $array;
    }
}
