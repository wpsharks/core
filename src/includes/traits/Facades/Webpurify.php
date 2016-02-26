<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;

trait Webpurify
{
    /**
     * @since 151214 Adding functions.
     */
    public static function containsBadWords(...$args)
    {
        return $GLOBALS[static::class]->Utils->©WebPurify->check(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function slugContainsBadWords(...$args)
    {
        return $GLOBALS[static::class]->Utils->©WebPurify->checkSlug(...$args);
    }
}
