<?php
/**
 * Request-type.
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
 * Request-type.
 *
 * @since 160531
 */
trait RequestType
{
    /**
     * @since 160531 Request types.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\RequestType::isAjax()
     */
    public static function isAjax(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RequestType->isAjax(...$args);
    }

    /**
     * @since 160531 Request types.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\RequestType::isApi()
     */
    public static function isApi(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RequestType->isApi(...$args);
    }

    /**
     * @since 160608 Request types.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\RequestType::doingRestAction()
     */
    public static function doingRestAction(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RequestType->doingRestAction(...$args);
    }

    /**
     * @since 17xxxx Action utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\RequestType::doingAction()
     */
    public static function doingAction(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RequestType->doingAction(...$args);
    }

    /**
     * @since 17xxxx Action utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\RequestType::didAction()
     */
    public static function didAction(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RequestType->didAction(...$args);
    }
}
