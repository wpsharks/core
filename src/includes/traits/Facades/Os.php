<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;

trait Os
{
    /**
     * @since 151214 Adding functions.
     */
    public static function isUnix(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Os->isUnix(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function isLinux(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Os->isLinux(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function isMac(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Os->isMac(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function isWindows(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Os->isWindows(...$args);
    }
}
