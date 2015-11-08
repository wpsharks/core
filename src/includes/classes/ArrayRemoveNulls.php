<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * Array (w/o null values).
 *
 * @since 15xxxx Adding array utils.
 */
class ArrayRemoveNulls extends AbsBase
{
    /**
     * Class constructor.
     *
     * @since 15xxxx Adding array utils.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Remove null values.
     *
     * @since 15xxxx Adding array utils.
     *
     * @param array $array Input array to iterate.
     *
     * @return array Array w/o null values.
     */
    public function __invoke(array $array): array
    {
        foreach ($array as $_key => &$_value) {
            if (!isset($_value)) {
                unset($array[$_key]);
            } elseif (is_array($_value)) {
                $_value = $this->__invoke($_value);
            }
        } // unset($_key, $_value); // Housekeeping.

        return $array;
    }
}
