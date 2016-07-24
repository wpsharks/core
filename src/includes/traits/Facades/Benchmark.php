<?php
/**
 * Benchmark.
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
 * Benchmark.
 *
 * @since 151214
 */
trait Benchmark
{
    /**
     * @since 151214 First facades.
     */
    public static function benchStart(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Benchmark->start(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function benchPrint(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Benchmark->print(...$args);
    }
}
