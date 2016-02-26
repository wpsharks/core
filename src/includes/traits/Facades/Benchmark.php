<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;

trait Benchmark
{
    /**
     * @since 151214 Adding functions.
     */
    public static function benchStart(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Benchmark->start(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function benchPrint(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Benchmark->print(...$args);
    }
}
