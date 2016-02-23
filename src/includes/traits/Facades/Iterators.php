<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;

trait Iterators
{
    /**
     * @since 151214 Adding functions.
     */
    public static function arrayRecursiveIterator(...$args)
    {
        return $GLOBALS[static::class]->Utils->Iterators->arrayRecursive(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function dirRegexRecursiveIterator(...$args)
    {
        return $GLOBALS[static::class]->Utils->Iterators->dirRecursiveRegex(...$args);
    }
}
