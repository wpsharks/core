<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;

trait Base64
{
    /**
     * @since 151214 Adding functions.
     */
    public static function base64UrlSafeEncode(...$args)
    {
        return $GLOBALS[static::class]->Utils->Base64->urlSafeEncode(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function base64UrlSafeDecode(...$args)
    {
        return $GLOBALS[static::class]->Utils->Base64->urlSafeDecode(...$args);
    }
}
