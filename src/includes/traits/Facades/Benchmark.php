<?php
/**
 * Benchmark.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
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
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Benchmark::start()
     */
    public static function benchStart(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Benchmark->start(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Benchmark::print()
     */
    public static function benchPrint(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Benchmark->print(...$args);
    }
}
