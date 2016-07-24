<?php
/**
 * Current URL.
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
 * Current URL.
 *
 * @since 151214
 */
trait UrlCurrent
{
    /**
     * @since 151214 First facades.
     */
    public static function isSsl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlCurrent->isSsl(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function isLocalhost(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlCurrent->isLocalhost(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function currentUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlCurrent->__invoke(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function currentScheme(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlCurrent->scheme(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function currentHost(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlCurrent->host(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function currentRootHost(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlCurrent->rootHost(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function currentUri(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlCurrent->uri(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function currentPath(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlCurrent->path(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function currentPathInfo(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlCurrent->pathInfo(...$args);
    }
}
