<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;

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
