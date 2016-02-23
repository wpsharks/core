<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;

trait App
{
    /**
     * @since 160102 App.
     */
    public static function app()
    {
        return $GLOBALS[static::class];
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function diGet(...$args)
    {
        return $GLOBALS[static::class]->Di->get(...$args);
    }
}
