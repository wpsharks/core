<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function fill_replacement_codes(...$args)
{
    return $GLOBALS[App::class]->Utils->ReplaceCodes->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function str_replace_once(...$args)
{
    return $GLOBALS[App::class]->Utils->ReplaceOnce->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function str_ireplace_once(...$args)
{
    return $GLOBALS[App::class]->Utils->ReplaceOnce->i(...$args);
}
