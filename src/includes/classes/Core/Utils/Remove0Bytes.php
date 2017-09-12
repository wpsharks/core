<?php
/**
 * Remove zero bytes.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
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
 * Remove zero bytes.
 *
 * @since 160428 Remove zero bytes.
 */
class Remove0Bytes extends Classes\Core\Base\Core
{
    /**
     * Remove zero bytes.
     *
     * @since 160428 Remove zero bytes.
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
            foreach ($value as $_key => &$_data) {
                if ($_data === '') {
                    if ($is_array) {
                        unset($value[$_key]);
                    } elseif ($is_object) {
                        unset($value->{$_key});
                    }
                } elseif (is_array($_data) || is_object($_data)) {
                    $_data = $this->__invoke($_data);
                }
            } // unset($_key, $_data);
        }
        return $value;
    }
}
