<?php
/**
 * Errors.
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
 * Errors.
 *
 * @since 160710
 */
trait Errors
{
    /**
     * @since 160710 Error utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Error::__invoke()
     */
    public static function error(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Error->__invoke(...$args);
    }

    /**
     * @since 17xxxx Error utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Error::__invoke()
     */
    public static function errors(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Error->__invoke(...$args);
    }

    /**
     * @since 160710 Error utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Error::is()
     */
    public static function isError(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Error->is(...$args);
    }

    /**
     * @since 17xxxx Error utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Error::is()
     */
    public static function isErrors(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Error->is(...$args);
    }
}
