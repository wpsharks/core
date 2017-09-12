<?php
/**
 * Request utils.
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
 * Request utils.
 *
 * @since 17xxxx
 */
trait Request
{
    /**
     * @since 17xxxx Request utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Request::current()
     */
    public static function currentRequest(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Request->current(...$args);
    }

    /**
     * @since 17xxxx Request utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Request::create()
     */
    public static function createRequest(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Request->create(...$args);
    }
}
