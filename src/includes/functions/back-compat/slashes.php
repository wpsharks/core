<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function slash(...$args)
{
    return $GLOBALS[App::class]->Utils->Slashes->add(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function unslash(...$args)
{
    return $GLOBALS[App::class]->Utils->Slashes->remove(...$args);
}
