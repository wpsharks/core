<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;

trait Multibyte
{
    /**
     * @since 151214 Adding functions.
     */
    public static function isUtf8(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Utf8->isValid(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function mbLcFirst(...$args)
    {
        return $GLOBALS[static::class]->Utils->©MbLcFirst->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function mbStrCaseCmp(...$args)
    {
        return $GLOBALS[static::class]->Utils->©StrCaseCmp->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function mbStrPad(...$args)
    {
        return $GLOBALS[static::class]->Utils->©StrPad->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function mbStrRev(...$args)
    {
        return $GLOBALS[static::class]->Utils->©StrRev->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function mbStrSplit(...$args)
    {
        return $GLOBALS[static::class]->Utils->©StrSplit->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function mbSubstrReplace(...$args)
    {
        return $GLOBALS[static::class]->Utils->©SubstrReplace->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function mbTrim(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Trim->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function mbLTrim(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Trim->l(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function mbRTrim(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Trim->r(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function mbUcFirst(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UcFirst->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function mbUcWords(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UcWords->__invoke(...$args);
    }
}
