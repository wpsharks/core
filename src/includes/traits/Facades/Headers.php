<?php
/**
 * Headers.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
//
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
//
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Headers.
 *
 * @since 151214
 */
trait Headers
{
    /**
     * @since 170824.30708 Current headers.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Headers::current()
     */
    public static function currentHeaders(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Headers->current(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Headers::sendStatus()
     */
    public static function statusHeader(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Headers->sendStatus(...$args);
    }

    /**
     * @since 170824.30708 Status header slug.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Headers::getStatusSlug()
     */
    public static function statusHeaderSlug(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Headers->getStatusSlug(...$args);
    }

    /**
     * @since 170824.30708 Status header message.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Headers::getStatusMessage()
     */
    public static function statusHeaderMessage(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Headers->getStatusMessage(...$args);
    }

    /**
     * @since 17xxxx Status header sentence.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Headers::getStatusSentence()
     */
    public static function statusHeaderSentence(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Headers->getStatusSentence(...$args);
    }

    /**
     * @since 17xxxx Yes-cache headers.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Headers::yesCache()
     */
    public static function yesCacheHeadersArray(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Headers->yesCache(...$args);
    }

    /**
     * @since 17xxxx No-cache headers.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Headers::noCache()
     */
    public static function noCacheHeadersArray(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Headers->noCache(...$args);
    }

    /**
     * @since 17xxxx Yes-cache headers.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Headers::yesCacheSend()
     */
    public static function yesCacheHeaders(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Headers->yesCacheSend(...$args);
    }

    /**
     * @since 160118 Adding no-cache headers.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Headers::noCacheSend()
     */
    public static function noCacheHeaders(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Headers->noCacheSend(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Headers::parse()
     */
    public static function parseHeaders(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Headers->parse(...$args);
    }
}
