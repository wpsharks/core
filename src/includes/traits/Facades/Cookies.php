<?php
/**
 * Cookies.
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
 * Cookies.
 *
 * @since 151214
 */
trait Cookies
{
    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Cookie::get()
     */
    public static function getCookie(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Cookie->get(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Cookie::set()
     */
    public static function setCookie(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Cookie->set(...$args);
    }
}
