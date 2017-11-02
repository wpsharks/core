<?php
/**
 * Hash ID utils.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Hash ID utils.
 *
 * @since 17xxxx
 */
trait HashIds
{
    /**
     * @since 161003 Encode one ID.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\HashIds::encode()
     */
    public static function hashId(...$args)
    {
        return $GLOBALS[static::class]->Utils->©HashIds->encode(...$args);
    }

    /**
     * @since 161003 One decoded ID.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\HashIds::decodeOne()
     */
    public static function decodeHashId(...$args)
    {
        return $GLOBALS[static::class]->Utils->©HashIds->decodeOne(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\HashIds::encode()
     */
    public static function hashIds(...$args)
    {
        return $GLOBALS[static::class]->Utils->©HashIds->encode(...$args);
    }

    /**
     * @since 160630 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\HashIds::decode()
     */
    public static function decodeHashedIds(...$args)
    {
        return $GLOBALS[static::class]->Utils->©HashIds->decode(...$args);
    }
}
