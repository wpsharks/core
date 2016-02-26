<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;

trait UrlBuilders
{
    /**
     * @since 151214 Adding functions.
     */
    public static function setScheme(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlScheme->set(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function appUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Url->toApp(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function appCoreUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Url->toAppCore(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function curUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Url->toCurrent(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function curCoreUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Url->toCurrentCore(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function cdnUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Cdn->url(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function cdnS3Url(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Cdn->s3Url(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function cdnFilter(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Cdn->filter(...$args);
    }
}
