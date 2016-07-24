<?php
/**
 * Remove specific keys.
 *
 * @author @jaswsinc
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
 * Remove specific keys.
 *
 * @since 160720 Remove key utils.
 */
class RemoveKeys extends Classes\Core\Base\Core
{
    /**
     * Remove specific keys.
     *
     * @since 160720 Remove key utils.
     *
     * @param array|object     $value Input value.
     * @param int|string|array $keys  Keys to remove.
     *
     * @return array|object Output value.
     */
    public function __invoke($value, $keys)
    {
        $is_array  = is_array($value);
        $is_object = !$is_array && is_object($value);
        $keys      = (array) $keys; // Force array.

        if ($is_array || $is_object) {
            foreach ($value as $_key => &$_data) {
                if (in_array($_key, $keys, true)) {
                    unset($value[$_key]);
                } elseif (is_array($_data) || is_object($_data)) {
                    $_data = $this->__invoke($_data, $keys);
                }
            } // unset($_key, $_data);
        }
        return $value;
    }
}
