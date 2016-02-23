<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;

trait UrlCurrent
{
    /**
     * @since 151214 Adding functions.
     */
    public static function isSsl(...$args)
    {
        return $GLOBALS[static::class]->Utils->UrlCurrent->isSsl(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function isLocalhost(...$args)
    {
        return $GLOBALS[static::class]->Utils->UrlCurrent->isLocalhost(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function currentUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->UrlCurrent->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function currentScheme(...$args)
    {
        return $GLOBALS[static::class]->Utils->UrlCurrent->scheme(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function currentHost(...$args)
    {
        return $GLOBALS[static::class]->Utils->UrlCurrent->host(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function currentRootHost(...$args)
    {
        return $GLOBALS[static::class]->Utils->UrlCurrent->rootHost(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function currentUri(...$args)
    {
        return $GLOBALS[static::class]->Utils->UrlCurrent->uri(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function currentPath(...$args)
    {
        return $GLOBALS[static::class]->Utils->UrlCurrent->path(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function currentPathInfo(...$args)
    {
        return $GLOBALS[static::class]->Utils->UrlCurrent->pathInfo(...$args);
    }
}
