<?php
/**
 * URL querys.
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
 * URL querys.
 *
 * @since 151214
 */
trait UrlQuerys
{
    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UrlQuery::strip()
     */
    public static function stripUrlQuery(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlQuery->strip(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UrlQuery::parse()
     */
    public static function parseUrlQuery(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlQuery->parse(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UrlQuery::build()
     */
    public static function buildUrlQuery(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlQuery->build(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UrlQuery::addArgs()
     */
    public static function addUrlQueryArgs(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlQuery->addArgs(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UrlQuery::removeArgs()
     */
    public static function removeUrlQueryArgs(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlQuery->removeArgs(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UrlQuery::addSha256Sig()
     */
    public static function addUrlQuerySig(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlQuery->addSha256Sig(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UrlQuery::removeSha256Sig()
     */
    public static function removeUrlQuerySig(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlQuery->removeSha256Sig(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UrlQuery::sha256SigOk()
     */
    public static function urlQuerySigOk(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlQuery->sha256SigOk(...$args);
    }
}
