<?php
/**
 * Deprecated.
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
 * Deprecated.
 *
 * @since 170309.60830
 */
trait Deprecated
{
    /**
     * @since 170309.60830 Back compat.
     * @deprecated 170309.60830 Use `encrypt()`.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Rijndael256::encrypt()
     */
    public static function rjEncrypt(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Rijndael256->encrypt(...$args);
    }

    /**
     * @since 170309.60830 Back compat.
     * @deprecated 170309.60830 Use `decrypt()`.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Rijndael256::decrypt()
     */
    public static function rjDecrypt(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Rijndael256->decrypt(...$args);
    }
}
