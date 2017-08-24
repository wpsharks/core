<?php
/**
 * Boolean utils.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Boolean utils.
 *
 * @since 170824.30708 Boolean utils.
 */
class Boolean extends Classes\Core\Base\Core
{
    /**
     * Test for truthy.
     *
     * @since 170824.30708 Boolean utils.
     *
     * @param mixed $value   Value to test.
     * @param mixed $default Default if `!isset($value)`?
     *
     * @return bool|mixed True if truthy, else false.
     * @note If `$default` is passed and `!isset($value)`, returns `$default`.
     */
    public function isTruthy($value, $default = null)
    {
        if (!isset($value) && func_num_args() >= 2) {
            return $default;
        }
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Test for falsy.
     *
     * @since 170824.30708 Boolean utils.
     *
     * @param mixed $value   Value to test.
     * @param mixed $default Default if `!isset($value)`?
     *
     * @return bool|mixed True if falsy, else false.
     * @note If `$default` is passed and `!isset($value)`, returns `$default`.
     */
    public function isFalsy($value, $default = null)
    {
        if (!isset($value) && func_num_args() >= 2) {
            return $default;
        }
        return !filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * To `yes` or `no`.
     *
     * @since 170824.30708 Boolean utils.
     *
     * @param mixed $value   Value to test.
     * @param mixed $default Default if `!isset($value)`?
     *
     * @return string|mixed `yes` or `no`.
     * @note If `$default` is passed and `!isset($value)`, returns `$default`.
     */
    public function yesNo($value, $default = null)
    {
        if (!isset($value) && func_num_args() >= 2) {
            return $default;
        }
        return $this->isTruthy($value) ? 'yes' : 'no';
    }

    /**
     * To `on` or `off`.
     *
     * @since 170824.30708 Boolean utils.
     *
     * @param mixed $value   Value to test.
     * @param mixed $default Default if `!isset($value)`?
     *
     * @return string|mixed `on` or `off`.
     * @note If `$default` is passed and `!isset($value)`, returns `$default`.
     */
    public function onOff($value, $default = null)
    {
        if (!isset($value) && func_num_args() >= 2) {
            return $default;
        }
        return $this->isTruthy($value) ? 'on' : 'off';
    }

    /**
     * To `true` or `false`.
     *
     * @since 170824.30708 Boolean utils.
     *
     * @param mixed $value   Value to test.
     * @param mixed $default Default if `!isset($value)`?
     *
     * @return string|mixed `true` or `false`.
     * @note If `$default` is passed and `!isset($value)`, returns `$default`.
     */
    public function trueFalse($value, $default = null)
    {
        if (!isset($value) && func_num_args() >= 2) {
            return $default;
        }
        return $this->isTruthy($value) ? 'true' : 'false';
    }

    /**
     * To `1` or `0`.
     *
     * @since 170824.30708 Boolean utils.
     *
     * @param mixed $value   Value to test.
     * @param mixed $default Default if `!isset($value)`?
     *
     * @return string|mixed `1` or `0`.
     * @note If `$default` is passed and `!isset($value)`, returns `$default`.
     */
    public function oneZero($value, $default = null)
    {
        if (!isset($value) && func_num_args() >= 2) {
            return $default;
        }
        return $this->isTruthy($value) ? '1' : '0';
    }
}
