<?php
/**
 * Multibyte.
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
 * Multibyte.
 *
 * @since 151214
 */
trait Multibyte
{
    /**
     * @since 151214 First facades.
     */
    public static function isUtf8(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Utf8->isValid(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function mbLcFirst(...$args)
    {
        return $GLOBALS[static::class]->Utils->©MbLcFirst->__invoke(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function mbStrCaseCmp(...$args)
    {
        return $GLOBALS[static::class]->Utils->©StrCaseCmp->__invoke(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function mbStrPad(...$args)
    {
        return $GLOBALS[static::class]->Utils->©StrPad->__invoke(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function mbStrRev(...$args)
    {
        return $GLOBALS[static::class]->Utils->©StrRev->__invoke(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function mbStrSplit(...$args)
    {
        return $GLOBALS[static::class]->Utils->©StrSplit->__invoke(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function mbSubstrReplace(...$args)
    {
        return $GLOBALS[static::class]->Utils->©SubstrReplace->__invoke(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function mbTrim(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Trim->__invoke(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function mbLTrim(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Trim->l(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function mbRTrim(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Trim->r(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function mbUcFirst(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UcFirst->__invoke(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function mbUcWords(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UcWords->__invoke(...$args);
    }
}
