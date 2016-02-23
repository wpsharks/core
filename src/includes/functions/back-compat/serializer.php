<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function maybe_serialize(...$args)
{
    return $GLOBALS[App::class]->Utils->Serializer->maybeSerialize(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function maybe_unserialize(...$args)
{
    return $GLOBALS[App::class]->Utils->Serializer->maybeUnserialize(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function check_set_unserialized_type(...$args)
{
    return $GLOBALS[App::class]->Utils->Serializer->checkSetType(...$args);
}
