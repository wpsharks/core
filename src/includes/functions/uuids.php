<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function uuid_v1(...$args)
{
    return $GLOBALS[App::class]->Utils->Uuid->v1(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function uuid_v3(...$args)
{
    return $GLOBALS[App::class]->Utils->Uuid->v3(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function uuid_v4(...$args)
{
    return $GLOBALS[App::class]->Utils->Uuid->v4(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function uuid_v5(...$args)
{
    return $GLOBALS[App::class]->Utils->Uuid->v5(...$args);
}
