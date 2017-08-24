<?php
/**
 * UUIDs.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * UUIDs.
 *
 * @since 151214
 */
trait Uuids
{
    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Uuid::v1()
     */
    public static function uuidV1(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Uuid->v1(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Uuid::v3()
     */
    public static function uuidV3(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Uuid->v3(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Uuid::v4()
     */
    public static function uuidV4(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Uuid->v4(...$args);
    }

    /**
     * @since 170824.30708 UUID v4 x 2.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Uuid::v4x2()
     */
    public static function uuidV4x2(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Uuid->v4x2(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Uuid::v5()
     */
    public static function uuidV5(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Uuid->v5(...$args);
    }
}
