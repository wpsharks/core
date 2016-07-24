<?php
/**
 * Memcache.
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
 * Memcache.
 *
 * @since 151214
 */
trait Memcache
{
    /**
     * @since 151214 First facades.
     */
    public static function memcacheSet(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Memcache->set(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function memcacheGet(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Memcache->get(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function memcacheTouch(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Memcache->touch(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function memcacheClear(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Memcache->clear(...$args);
    }
}
