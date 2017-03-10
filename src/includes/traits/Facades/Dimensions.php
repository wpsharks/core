<?php
/**
 * Dimensions.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Dimensions.
 *
 * @since 151214
 */
trait Dimensions
{
    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\OneDimension::__invoke()
     */
    public static function oneDimension(...$args)
    {
        return $GLOBALS[static::class]->Utils->©OneDimension->__invoke(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\RemoveEmptys::__invoke()
     */
    public static function removeEmptys(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RemoveEmptys->__invoke(...$args);
    }

    /**
     * @since 160428 Remove 0 bytes.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Remove0Bytes::__invoke()
     */
    public static function remove0Bytes(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Remove0Bytes->__invoke(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\RemoveNulls::__invoke()
     */
    public static function removeNulls(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RemoveNulls->__invoke(...$args);
    }

    /**
     * @since 160720 Remove key utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\RemoveKeys::__invoke()
     */
    public static function removeKey(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RemoveKeys->__invoke(...$args);
    }

    /**
     * @since 160720 Remove key utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\RemoveKeys::__invoke()
     */
    public static function removeKeys(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RemoveKeys->__invoke(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Sort::byKey()
     */
    public static function sortByKey(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Sort->byKey(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\DotKeys::__invoke()
     */
    public static function dotKeys(...$args)
    {
        return $GLOBALS[static::class]->Utils->©DotKeys->__invoke(...$args);
    }
}
