<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 160102 App.
 */
function app() // App instance.
{
    return $GLOBALS[App::class];
}

/**
 * @since 151214 Adding functions.
 */
function di_get(...$args)
{
    return $GLOBALS[App::class]->Di->get(...$args);
}
