<?php
/**
 * Defuse utils.
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
 * Defuse utils.
 *
 * @since 17xxxx
 */
trait Defuse
{
    /**
     * @since 151214 First facades.
     * @since 170309.60830 Switched to Defuse.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Defuse::encrypt()
     */
    public static function encrypt(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Defuse->encrypt(...$args);
    }

    /**
     * @since 151214 First facades.
     * @since 170309.60830 Switched to Defuse.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Defuse::decrypt()
     */
    public static function decrypt(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Defuse->decrypt(...$args);
    }

    /**
     * @since 170309.60830 Defuse keygen.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Defuse::keygen()
     */
    public static function encryptionKey(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Defuse->keygen(...$args);
    }
}
