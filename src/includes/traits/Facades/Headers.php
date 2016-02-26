<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;

trait Headers
{
    /**
     * @since 151214 Adding functions.
     */
    public static function statusHeader(...$args)
    {
        return $GLOBALS[static::class]->Utils->Headers->sendStatus(...$args);
    }

    /**
     * @since 160118 Adding no-cache headers.
     */
    public static function noCacheHeaders(...$args)
    {
        return $GLOBALS[static::class]->Utils->Headers->sendNoCache(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function parseHeaders(...$args)
    {
        return $GLOBALS[static::class]->Utils->Headers->parse(...$args);
    }
}
