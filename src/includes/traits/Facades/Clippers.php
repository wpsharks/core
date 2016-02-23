<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;

trait Clippers
{
    /**
     * @since 151214 Adding functions.
     */
    public static function clip(...$args)
    {
        return $GLOBALS[static::class]->Utils->Clip->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function midClip(...$args)
    {
        return $GLOBALS[static::class]->Utils->MidClip->__invoke(...$args);
    }
}
