<?php
/**
 * CSRF utils.
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
 * CSRF utils.
 *
 * @since 17xxxx
 */
trait Csrf
{
    /**
     * @since 17xxxx CSRF utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Csrf::create()
     */
    public static function createCsrf(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Csrf->create(...$args);
    }

    /**
     * @since 17xxxx CSRF utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Csrf::input()
     */
    public static function csrfInput(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Csrf->input(...$args);
    }

    /**
     * @since 17xxxx CSRF utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Csrf::verify()
     */
    public static function verifyCsrf(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Csrf->verify(...$args);
    }
}
