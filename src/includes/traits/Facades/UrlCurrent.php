<?php
/**
 * Current URL.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare(strict_types=1);
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
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UrlCurrent::isSsl()
     */
    public static function isSsl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlCurrent->isSsl(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UrlCurrent::isLocalhost()
     */
    public static function isLocalhost(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlCurrent->isLocalhost(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UrlCurrent::__invoke()
     */
    public static function currentUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlCurrent->__invoke(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UrlCurrent::scheme()
     */
    public static function currentScheme(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlCurrent->scheme(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UrlCurrent::host()
     */
    public static function currentHost(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlCurrent->host(...$args);
    }

    /**
     * @since 16xxxx Adding port utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UrlCurrent::port()
     */
    public static function currentPort(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlCurrent->port(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UrlCurrent::rootHost()
     */
    public static function currentRootHost(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlCurrent->rootHost(...$args);
    }

    /**
     * @since 16xxxx Adding port utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UrlCurrent::rootPort()
     */
    public static function currentRootPort(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlCurrent->rootPort(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UrlCurrent::uri()
     */
    public static function currentUri(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlCurrent->uri(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UrlCurrent::path()
     */
    public static function currentPath(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlCurrent->path(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UrlCurrent::pathInfo()
     */
    public static function currentPathInfo(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlCurrent->pathInfo(...$args);
    }
}
