<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;

trait Slashes
{
    /**
     * @since 151214 Adding functions.
     */
    public static function slash(...$args)
    {
        return $GLOBALS[static::class]->Utils->Slashes->add(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function unslash(...$args)
    {
        return $GLOBALS[static::class]->Utils->Slashes->remove(...$args);
    }
}
