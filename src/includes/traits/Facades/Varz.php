<?php
/**
 * Vars.
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
 * Vars.
 *
 * @since 160220
 */
trait Varz
{
    /**
     * @since 160220 Var utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Varz::isValid()
     */
    public static function isVar(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Varz->isValid(...$args);
    }

    /**
     * @since 160220 Var utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Varz::toName()
     */
    public static function varToName(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Varz->toName(...$args);
    }

    /**
     * @since 160220 Var utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Varz::toAcronym()
     */
    public static function varToAcronym(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Varz->toAcronym(...$args);
    }

    /**
     * @since 160220 Var utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Varz::toSlug()
     */
    public static function varToSlug(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Varz->toSlug(...$args);
    }

    /**
     * @since 170413.34876 Var utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Varz::toCamelCase()
     */
    public static function varToCamelCase(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Varz->toCamelCase(...$args);
    }
}
