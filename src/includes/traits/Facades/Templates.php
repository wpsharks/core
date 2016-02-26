<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;

trait Templates
{
    /**
     * @since 160118 Template locater.
     */
    public static function locateTemplate(...$args)
    {
        return $GLOBALS[static::class]->Utils->Template->locate(...$args);
    }

    /**
     * @since 160118 Router templates.
     */
    public static function locateRouteTemplate(...$args)
    {
        return $GLOBALS[static::class]->Utils->Template->locateRoute(...$args);
    }

    /**
     * @since 160118 Router templates.
     */
    public static function loadRouteTemplate(...$args)
    {
        return $GLOBALS[static::class]->Utils->Template->loadRoute(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function getTemplate(...$args)
    {
        return $GLOBALS[static::class]->Utils->Template->get(...$args);
    }
}
