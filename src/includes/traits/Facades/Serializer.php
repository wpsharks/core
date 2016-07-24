<?php
/**
 * Serializer.
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
 * Serializer.
 *
 * @since 160712
 */
trait Serializer
{
    /**
     * @since 160712 Serializer.
     */
    public static function serialize(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Serializer->__invoke(...$args);
    }

    /**
     * @since 160712 Serializer.
     */
    public static function serializeClosure(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Serializer->serializeClosure(...$args);
    }

    /**
     * @since 160712 Serializer.
     */
    public static function unserializeClosure(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Serializer->unserializeClosure(...$args);
    }

    /**
     * @since 151214 Serializer.
     */
    public static function maybeSerialize(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Serializer->maybeSerialize(...$args);
    }

    /**
     * @since 151214 Serializer.
     */
    public static function maybeUnserialize(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Serializer->maybeUnserialize(...$args);
    }

    /**
     * @since 160712 Serializer.
     */
    public static function isSerialized(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Serializer->isSerialized(...$args);
    }

    /**
     * @since 151214 Serializer.
     */
    public static function checkSetUnserializedType(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Serializer->checkSetType(...$args);
    }
}
