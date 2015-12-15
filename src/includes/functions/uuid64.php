<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function uuid64_validate(...$args)
{
    return $GLOBALS[App::class]->Utils->Uuid64->validate(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function uuid64_shard_id_in(...$args)
{
    return $GLOBALS[App::class]->Utils->Uuid64->shardIdIn(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function uuid64_validate_shard_id(...$args)
{
    return $GLOBALS[App::class]->Utils->Uuid64->validateShardId(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function uuid64_type_id_in(...$args)
{
    return $GLOBALS[App::class]->Utils->Uuid64->typeIdIn(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function uuid64_validate_type_id(...$args)
{
    return $GLOBALS[App::class]->Utils->Uuid64->validateTypeId(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function uuid64_local_id_in(...$args)
{
    return $GLOBALS[App::class]->Utils->Uuid64->localIdIn(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function uuid64_validate_local_id(...$args)
{
    return $GLOBALS[App::class]->Utils->Uuid64->validateLocalId(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function uuid64_parse(...$args)
{
    return $GLOBALS[App::class]->Utils->Uuid64->parse(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function uuid64_build(...$args)
{
    return $GLOBALS[App::class]->Utils->Uuid64->build(...$args);
}
