<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;

trait Memcache
{
    /**
     * @since 151214 Adding functions.
     */
    public static function memcacheSet(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Memcache->set(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function memcacheGet(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Memcache->get(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function memcacheTouch(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Memcache->touch(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function memcacheClear(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Memcache->clear(...$args);
    }
}
