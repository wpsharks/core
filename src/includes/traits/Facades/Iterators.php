<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

trait Iterators
{
    /**
     * @since 151214 Adding functions.
     */
    public static function arrayRecursiveIterator(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Iterators->arrayRecursive(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function dirRegexRecursiveIterator(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Iterators->dirRecursiveRegex(...$args);
    }
}
