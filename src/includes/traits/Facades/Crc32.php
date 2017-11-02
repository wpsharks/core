<?php
/**
 * CRC-32 utils.
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
 * CRC-32 utils.
 *
 * @since 17xxxx CRC-32 utils.
 */
trait Crc32
{
    /**
     * @since 17xxxx CRC-32 utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Crc32::is()
     */
    public static function isCrc32(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Crc32->is(...$args);
    }

    /**
     * @since 170824.30708 CRC-32 utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Crc32::keyedHash()
     */
    public static function crc32KeyedHash(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Crc32->keyedHash(...$args);
    }
}
