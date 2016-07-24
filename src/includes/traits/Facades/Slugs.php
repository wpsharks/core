<?php
/**
 * Slugs.
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
 * Slugs.
 *
 * @since 151214
 */
trait Slugs
{
    /**
     * @since 151214 First facades.
     */
    public static function isSlug(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Slug->isValid(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function isSlugReserved(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Slug->isReserved(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function slugToName(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Slug->toName(...$args);
    }

    /**
     * @since 160220 Acronym utils.
     */
    public static function slugToAcronym(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Slug->toAcronym(...$args);
    }

    /**
     * @since 160220 Var utils.
     */
    public static function slugToVar(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Slug->toVar(...$args);
    }
}
