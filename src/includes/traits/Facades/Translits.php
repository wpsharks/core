<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;

trait Translits
{
    /**
     * @since 151214 Adding functions.
     */
    public static function forceAscii(...$args)
    {
        return $GLOBALS[static::class]->Utils->Transliterate->toAscii(...$args);
    }
}
