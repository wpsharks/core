<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;

trait Ips
{
    /**
     * @since 151214 Adding functions.
     */
    public static function currentIp(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Ip->current(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function ipRegion(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Ip->region(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function ipCountry(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Ip->country(...$args);
    }
}
