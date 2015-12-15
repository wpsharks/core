<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function is_slug(...$args)
{
    return $GLOBALS[App::class]->Utils->Slug->isValid(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function is_slug_reserved(...$args)
{
    return $GLOBALS[App::class]->Utils->Slug->isReserved(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function slug_to_name(...$args)
{
    return $GLOBALS[App::class]->Utils->Slug->toName(...$args);
}
