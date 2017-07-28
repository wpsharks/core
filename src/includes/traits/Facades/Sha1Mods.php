<?php
/**
 * SHA-1 modulus.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * SHA-1 modulus.
 *
 * @since 151214
 */
trait Sha1Mods
{
    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Sha1Mod::__invoke()
     */
    public static function sha1Mod(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Sha1Mod->__invoke(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Sha1Mod::shardId()
     */
    public static function sha1ModShardId(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Sha1Mod->shardId(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Sha1Mod::assignShardId()
     */
    public static function sha1ModAssignShardId(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Sha1Mod->assignShardId(...$args);
    }
}
