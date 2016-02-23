<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function one_dimension(...$args)
{
    return $GLOBALS[App::class]->Utils->OneDimension->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function remove_emptys(...$args)
{
    return $GLOBALS[App::class]->Utils->RemoveEmptys->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function remove_nulls(...$args)
{
    return $GLOBALS[App::class]->Utils->RemoveNulls->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function sort_by_key(...$args)
{
    return $GLOBALS[App::class]->Utils->Sort->byKey(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function dot_keys(...$args)
{
    return $GLOBALS[App::class]->Utils->DotKeys->__invoke(...$args);
}
