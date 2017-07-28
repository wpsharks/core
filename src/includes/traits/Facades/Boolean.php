<?php
/**
 * Boolean utils.
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
 * Boolean utils.
 *
 * @since 17xxxx Boolean utils.
 */
trait Boolean
{
    /**
     * @since 17xxxx Boolean utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Boolean::isTruthy()
     */
    public static function isTruthy(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Boolean->isTruthy(...$args);
    }

    /**
     * @since 17xxxx Boolean utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Boolean::isFalsy()
     */
    public static function isFalsy(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Boolean->isFalsy(...$args);
    }

    /**
     * @since 17xxxx Boolean utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Boolean::yesNo()
     */
    public static function yesNo(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Boolean->yesNo(...$args);
    }

    /**
     * @since 17xxxx Boolean utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Boolean::onOff()
     */
    public static function onOff(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Boolean->onOff(...$args);
    }

    /**
     * @since 17xxxx Boolean utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Boolean::trueFalse()
     */
    public static function trueFalse(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Boolean->trueFalse(...$args);
    }

    /**
     * @since 17xxxx Boolean utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Boolean::oneZero()
     */
    public static function oneZero(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Boolean->oneZero(...$args);
    }
}
