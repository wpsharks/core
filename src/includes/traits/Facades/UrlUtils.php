<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;

trait UrlUtils
{
    /**
     * @since 151214 Adding functions.
     */
    public static function isUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Url->isValid(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function stripUrlFragment(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlFragment->strip(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function normalizeUrlAmps(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Url->normalizeAmps(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function remoteRequest(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlRemote->request(...$args);
    }
}
