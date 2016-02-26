<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;

trait Dimensions
{
    /**
     * @since 151214 Adding functions.
     */
    public static function oneDimension(...$args)
    {
        return $GLOBALS[static::class]->Utils->©OneDimension->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function removeEmptys(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RemoveEmptys->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function removeNulls(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RemoveNulls->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function sortByKey(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Sort->byKey(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function dotKeys(...$args)
    {
        return $GLOBALS[static::class]->Utils->©DotKeys->__invoke(...$args);
    }
}
