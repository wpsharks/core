<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function benchmark_start(...$args)
{
    return $GLOBALS[App::class]->Utils->Benchmark->start(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function benchmark_print(...$args)
{
    return $GLOBALS[App::class]->Utils->Benchmark->print(...$args);
}
