<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function get_defined_vars as vars;

trait Files
{
    /**
     * @since 151214 Adding functions.
     */
    public static function fileExt(...$args)
    {
        return $GLOBALS[static::class]->Utils->©FileExt->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function fileSizeAbbr(...$args)
    {
        return $GLOBALS[static::class]->Utils->©FileSize->abbr(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function bytesToAbbr(...$args)
    {
        return $GLOBALS[static::class]->Utils->©FileSize->bytesAbbr(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function abbrToBytes(...$args)
    {
        return $GLOBALS[static::class]->Utils->©FileSize->abbrBytes(...$args);
    }
}
