<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function get_cookie(...$args)
{
    return $GLOBALS[App::class]->Utils->Cookie->get(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function set_cookie(...$args)
{
    return $GLOBALS[App::class]->Utils->Cookie->set(...$args);
}
