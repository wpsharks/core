<?php
/**
 * UUID-64.
 *
 * @author @jaswsinc
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
 * UUID-64.
 *
 * @since 151214
 */
trait Uuid64
{
    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UnshiftAssoc::__invoke()
     */
    public static function uuid64Validate(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Uuid64->validate(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UnshiftAssoc::__invoke()
     */
    public static function uuid64ShardIdIn(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Uuid64->shardIdIn(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UnshiftAssoc::__invoke()
     */
    public static function uuid64ValidateShardId(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Uuid64->validateShardId(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UnshiftAssoc::__invoke()
     */
    public static function uuid64TypeIdIn(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Uuid64->typeIdIn(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UnshiftAssoc::__invoke()
     */
    public static function uuid64ValidateTypeId(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Uuid64->validateTypeId(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UnshiftAssoc::__invoke()
     */
    public static function uuid64LocalIdIn(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Uuid64->localIdIn(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UnshiftAssoc::__invoke()
     */
    public static function uuid64ValidateLocalId(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Uuid64->validateLocalId(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UnshiftAssoc::__invoke()
     */
    public static function uuid64Parse(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Uuid64->parse(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UnshiftAssoc::__invoke()
     */
    public static function uuid64Build(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Uuid64->build(...$args);
    }
}
