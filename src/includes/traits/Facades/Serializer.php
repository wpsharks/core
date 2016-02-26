<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;

trait Serializer
{
    /**
     * @since 151214 Adding functions.
     */
    public static function maybeSerialize(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Serializer->maybeSerialize(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function maybeUnserialize(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Serializer->maybeUnserialize(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function checkSetUnserializedType(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Serializer->checkSetType(...$args);
    }
}
