<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function current_ip(...$args)
{
    return $GLOBALS[App::class]->Utils->Ip->current(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function ip_region(...$args)
{
    return $GLOBALS[App::class]->Utils->Ip->region(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function ip_country(...$args)
{
    return $GLOBALS[App::class]->Utils->Ip->country(...$args);
}
