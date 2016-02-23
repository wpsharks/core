<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * One dimension only.
 *
 * @since 150424 Initial release.
 */
class OneDimension extends Classes\Core
{
    /**
     * One dimension only.
     *
     * @since 150424 Enhance utils.
     *
     * @param array|object $value Input value.
     *
     * @return array|object Output value.
     */
    public function __invoke($value)
    {
        $is_array  = is_array($value);
        $is_object = !$is_array && is_object($value);

        if ($is_array || $is_object) {
            foreach ($value as $_key => $_data) {
                if (is_array($_data) || is_object($_data)) {
                    if ($is_array) {
                        unset($value[$_key]);
                    } elseif ($is_object) {
                        unset($value->{$_key});
                    }
                }
            } // unset($_key, $_data);
        }
        return $value;
    }
}
