<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function get_defined_vars as vars;

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
