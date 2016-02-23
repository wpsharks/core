<?php
declare (strict_types = 1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes\App;

/**
 * @since 151214 Adding functions.
 */
function sha1_mod(...$args)
{
    return $GLOBALS[App::class]->Utils->Sha1Mod->__invoke(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function sha1_mod_shard_id(...$args)
{
    return $GLOBALS[App::class]->Utils->Sha1Mod->shardId(...$args);
}

/**
 * @since 151214 Adding functions.
 */
function sha1_mod_assign_shard_id(...$args)
{
    return $GLOBALS[App::class]->Utils->Sha1Mod->assignShardId(...$args);
}
