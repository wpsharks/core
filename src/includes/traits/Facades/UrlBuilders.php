<?php
/**
 * URL builders.
 *
 * @author @jaswrks
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
 * URL builders.
 *
 * @since 151214
 */
trait UrlBuilders
{
    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UrlScheme::set()
     */
    public static function setScheme(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlScheme->set(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Url::toApp()
     */
    public static function appUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Url->toApp(...$args);
    }

    /**
     * @since 160423 Parent utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Url::toAppParent()
     */
    public static function appParentUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Url->toAppParent(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Url::toAppCore()
     */
    public static function appCoreUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Url->toAppCore(...$args);
    }

    /**
     * @since 160925 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Url::toAppWsCore()
     */
    public static function appWsCoreUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Url->toAppWsCore(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Url::toCurrent()
     */
    public static function curUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Url->toCurrent(...$args);
    }

    /**
     * @since 160423 Parent utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Url::toCurrentParent()
     */
    public static function curParentUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Url->toCurrentParent(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Url::toCurrentCore()
     */
    public static function curCoreUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Url->toCurrentCore(...$args);
    }

    /**
     * @since 160925 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Url::toCurrentWsCore()
     */
    public static function curWsCoreUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Url->toCurrentWsCore(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Cdn::url()
     */
    public static function cdnUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Cdn->url(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Cdn::filter()
     */
    public static function cdnFilter(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Cdn->filter(...$args);
    }
}
