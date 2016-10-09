<?php
/**
 * Templates.
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
 * Templates.
 *
 * @since 160118
 */
trait Templates
{
    /**
     * @since 160118 Template locater.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Template::locate()
     */
    public static function locateTemplate(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Template->locate(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Template::get()
     */
    public static function getTemplate(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Template->get(...$args);
    }
}
