<?php
/**
 * OS.
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
 * OS.
 *
 * @since 151214
 */
trait Os
{
    /**
     * @since 151214 First facades.
     */
    public static function isNix(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Os->isNix(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function isLinux(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Os->isLinux(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function isMac(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Os->isMac(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function isWindows(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Os->isWindows(...$args);
    }
}
