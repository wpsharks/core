<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;

trait Output
{
    /**
     * @since 151214 Adding functions.
     */
    public static function gzipOff(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Output->gzipOff(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function obEndCleanAll(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Output->buffersEndClean(...$args);
    }
}
