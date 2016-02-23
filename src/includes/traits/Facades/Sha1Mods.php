<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;

trait Sha1Mods
{
    /**
     * @since 151214 Adding functions.
     */
    public static function sha1Mod(...$args)
    {
        return $GLOBALS[static::class]->Utils->Sha1Mod->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function sha1ModShardId(...$args)
    {
        return $GLOBALS[static::class]->Utils->Sha1Mod->shardId(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function sha1ModAssignShardId(...$args)
    {
        return $GLOBALS[static::class]->Utils->Sha1Mod->assignShardId(...$args);
    }
}