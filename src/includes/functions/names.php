<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function fname_in(...$args)
{
    return $GLOBALS[App::class]->Utils->Name->firstIn(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function lname_in(...$args)
{
    return $GLOBALS[App::class]->Utils->Name->lastIn(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function name_to_slug(...$args)
{
    return $GLOBALS[App::class]->Utils->Name->toSlug(...$args);
}
