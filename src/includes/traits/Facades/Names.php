<?php
/**
 * Names.
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
 * Names.
 *
 * @since 151214
 */
trait Names
{
    /**
     * @since 151214 First facades.
     */
    public static function fnameIn(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Name->firstIn(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function lnameIn(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Name->lastIn(...$args);
    }

    /**
     * @since 160220 Acronym utils.
     */
    public static function nameToAcronym(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Name->toAcronym(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function nameToSlug(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Name->toSlug(...$args);
    }

    /**
     * @since 160220 Var utils.
     */
    public static function nameToVar(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Name->toVar(...$args);
    }
}
