<?php
/**
 * Routes.
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
 * Routes.
 *
 * @since 161008
 */
trait Routes
{
    /**
     * @since 161008 Route utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Route::load()
     */
    public static function loadRoute(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Route->load(...$args);
    }

    /**
     * @since 161008 Route utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Route::resolve()
     */
    public static function resolveRoute(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Route->resolve(...$args);
    }

    /**
     * @since 161008 Route utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Route::locate()
     */
    public static function locateRoute(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Route->locate(...$args);
    }

    /**
     * @since 161008 Route utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Route::get()
     */
    public static function getRoute(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Route->get(...$args);
    }
}
