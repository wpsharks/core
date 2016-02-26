<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;

trait Uuids
{
    /**
     * @since 151214 Adding functions.
     */
    public static function uuidV1(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Uuid->v1(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function uuidV3(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Uuid->v3(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function uuidV4(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Uuid->v4(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function uuidV5(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Uuid->v5(...$args);
    }
}
