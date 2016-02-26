<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;

trait Cookies
{
    /**
     * @since 151214 Adding functions.
     */
    public static function getCookie(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Cookie->get(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function setCookie(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Cookie->set(...$args);
    }
}
