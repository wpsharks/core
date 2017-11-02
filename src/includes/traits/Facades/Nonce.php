<?php
/**
 * Nonce utils.
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
 * Nonce utils.
 *
 * @since 17xxxx
 */
trait Nonce
{
    /**
     * @since 17xxxx Nonce utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Nonce::create()
     */
    public static function createNonce(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Nonce->create(...$args);
    }

    /**
     * @since 17xxxx Nonce utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Nonce::input()
     */
    public static function nonceInput(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Nonce->input(...$args);
    }

    /**
     * @since 17xxxx Nonce utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Nonce::verify()
     */
    public static function verifyNonce(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Nonce->verify(...$args);
    }
}
