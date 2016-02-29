<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;

trait App
{
    /**
     * @since 160102 App.
     */
    public static function app(...$args)
    {
        return $GLOBALS[static::class];
    }

    /**
     * @since 160227 App.
     */
    public static function config(...$args)
    {
        return $GLOBALS[static::class]->Config;
    }

    /**
     * @since 160227 App.
     */
    public static function version(...$args)
    {
        return $GLOBALS[static::class]::VERSION;
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function diGet(...$args)
    {
        return $GLOBALS[static::class]->Di->get(...$args);
    }
}
