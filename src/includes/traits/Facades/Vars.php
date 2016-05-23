<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function get_defined_vars as vars;

trait Vars
{
    /**
     * @since 160220 Var utils.
     */
    public static function isVar(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Vars->isValid(...$args);
    }

    /**
     * @since 160220 Var utils.
     */
    public static function varToName(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Vars->toName(...$args);
    }

    /**
     * @since 160220 Var utils.
     */
    public static function varToAcronym(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Vars->toAcronym(...$args);
    }

    /**
     * @since 160220 Var utils.
     */
    public static function varToSlug(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Vars->toSlug(...$args);
    }
}
