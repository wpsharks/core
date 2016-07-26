<?php
/**
 * Versions.
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
 * Versions.
 *
 * @since 151214
 */
trait Versions
{
    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Version::isValid()
     */
    public static function isVersion(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Version->isValid(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Version::isValidDev()
     */
    public static function isDevVersion(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Version->isValidDev(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Version::isValidStable()
     */
    public static function isStableVersion(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Version->isValidStable(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\WsVersion::isValid()
     */
    public static function isWsVersion(...$args)
    {
        return $GLOBALS[static::class]->Utils->©WsVersion->isValid(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\WsVersion::isValidDev()
     */
    public static function isWsDevVersion(...$args)
    {
        return $GLOBALS[static::class]->Utils->©WsVersion->isValidDev(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\WsVersion::isValidStable()
     */
    public static function isWsStableVersion(...$args)
    {
        return $GLOBALS[static::class]->Utils->©WsVersion->isValidStable(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\WsVersion::date()
     */
    public static function wsVersionDate(...$args)
    {
        return $GLOBALS[static::class]->Utils->©WsVersion->date(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\WsVersion::->time()
     */
    public static function wsVersionTime(...$args)
    {
        return $GLOBALS[static::class]->Utils->©WsVersion->time(...$args);
    }
}
