<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

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
