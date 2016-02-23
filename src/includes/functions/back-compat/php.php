<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function can_call_func(...$args)
{
    return $GLOBALS[App::class]->Utils->PhpHas->callableFunc(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function memory_limit(...$args)
{
    return $GLOBALS[App::class]->Utils->Memory->limit(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function strip_php_tags(...$args)
{
    return $GLOBALS[App::class]->Utils->PhpStrip->tags(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function upload_size_limit(...$args)
{
    return $GLOBALS[App::class]->Utils->UploadSize->limit(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function max_exec_time(...$args)
{
    return $GLOBALS[App::class]->Utils->ExecTime->max(...$args);
}
