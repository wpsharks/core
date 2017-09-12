<?php
/**
 * Arrays.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Traits\Facades;

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
 * Arrays.
 *
 * @since 160102
 */
trait Arrays
{
    /**
     * @since 160511 Array cloning.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\ArrayClone::__invoke()
     */
    public static function cloneArray(...$args)
    {
        return $GLOBALS[static::class]->Utils->©ArrayClone->__invoke(...$args);
    }

    /**
     * @since 17xxxx Change recursive.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\ArrayChangeRecursive::__invoke()
     */
    public static function arrayChangeRecursive(...$args)
    {
        return $GLOBALS[static::class]->Utils->©ArrayChangeRecursive->__invoke(...$args);
    }

    /**
     * @since 160102 App.
     * @see Classes\Core\Utils\ArrayUnshiftAssoc::__invoke()
     *
     * @param array      $array An input array; by reference.
     * @param string|int $key   New array key; string or integer.
     * @param mixed      $value New array value.
     */
    public static function arrayUnshiftAssoc(&$array, $key, $value)
    {
        return $GLOBALS[static::class]->Utils->©ArrayUnshiftAssoc->__invoke($array, $key, $value);
    }
}
