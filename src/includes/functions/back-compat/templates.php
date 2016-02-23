<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 160118 Template locater.
 */
function locate_template(...$args)
{
    return $GLOBALS[App::class]->Utils->Template->locate(...$args);
}

/**
 * @since 160118 Router templates.
 */
function locate_route_template(...$args)
{
    return $GLOBALS[App::class]->Utils->Template->locateRoute(...$args);
}

/**
 * @since 160118 Router templates.
 */
function load_route_template(...$args)
{
    return $GLOBALS[App::class]->Utils->Template->loadRoute(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function get_template(...$args)
{
    return $GLOBALS[App::class]->Utils->Template->get(...$args);
}
