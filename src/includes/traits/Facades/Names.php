<?php
/**
 * Names.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
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
 * Names.
 *
 * @since 151214
 */
trait Names
{
    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Name::firstIn()
     */
    public static function fnameIn(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Name->firstIn(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Name::lastIn()
     */
    public static function lnameIn(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Name->lastIn(...$args);
    }

    /**
     * @since 160220 Acronym utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Name::toAcronym()
     */
    public static function nameToAcronym(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Name->toAcronym(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Name::toSlug()
     */
    public static function nameToSlug(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Name->toSlug(...$args);
    }

    /**
     * @since 160220 Var utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Name::toVar()
     */
    public static function nameToVar(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Name->toVar(...$args);
    }
}
