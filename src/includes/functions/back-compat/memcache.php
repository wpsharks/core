<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function memcache_set(...$args)
{
    return $GLOBALS[App::class]->Utils->Memcache->set(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function memcache_get(...$args)
{
    return $GLOBALS[App::class]->Utils->Memcache->get(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function memcache_touch(...$args)
{
    return $GLOBALS[App::class]->Utils->Memcache->touch(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function memcache_clear(...$args)
{
    return $GLOBALS[App::class]->Utils->Memcache->clear(...$args);
}
