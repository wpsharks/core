<?php
/**
 * SHA-256 utils.
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
 * SHA-256 utils.
 *
 * @since 17xxxx SHA-256 utils.
 */
trait Sha256
{
    /**
     * @since 17xxxx SHA-256 utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Sha256::is()
     */
    public static function isSha256(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Sha256->is(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Sha256::keyedHash()
     */
    public static function sha256KeyedHash(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Sha256->keyedHash(...$args);
    }
}
