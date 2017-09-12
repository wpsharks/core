<?php
/**
 * Slashes.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Slashes.
 *
 * @since 151214
 */
trait Slashes
{
    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Slashes::add()
     */
    public static function slash(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Slashes->add(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Slashes::remove()
     */
    public static function unslash(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Slashes->remove(...$args);
    }
}
