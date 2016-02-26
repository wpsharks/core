<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;

trait Uuid64
{
    /**
     * @since 151214 Adding functions.
     */
    public static function uuid64Validate(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Uuid64->validate(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function uuid64ShardIdIn(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Uuid64->shardIdIn(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function uuid64ValidateShardId(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Uuid64->validateShardId(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function uuid64TypeIdIn(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Uuid64->typeIdIn(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function uuid64ValidateTypeId(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Uuid64->validateTypeId(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function uuid64LocalIdIn(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Uuid64->localIdIn(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function uuid64ValidateLocalId(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Uuid64->validateLocalId(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function uuid64Parse(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Uuid64->parse(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function uuid64Build(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Uuid64->build(...$args);
    }
}
