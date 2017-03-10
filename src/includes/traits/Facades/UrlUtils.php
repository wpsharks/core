<?php
/**
 * URL utils.
 *
 * @author @jaswrks
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
 * URL utils.
 *
 * @since 151214
 */
trait UrlUtils
{
    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Url::isValid()
     */
    public static function isUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Url->isValid(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UrlFragment::strip()
     */
    public static function stripUrlFragment(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlFragment->strip(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Url::normalizeAmps()
     */
    public static function normalizeUrlAmps(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Url->normalizeAmps(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UrlRemote::request()
     */
    public static function remoteRequest(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlRemote->request(...$args);
    }

    /**
     * @since 170215.53419 New URL utility.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UrlRemote::response()
     */
    public static function remoteResponse(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlRemote->response(...$args);
    }
}
