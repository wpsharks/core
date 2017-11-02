<?php
/**
 * Session utils.
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
 * Session utils.
 *
 * @since 17xxxx
 */
trait Session
{
    /**
     * @since 17xxxx Session utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Session::isActive()
     */
    public static function isActiveSession(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Session->isActive(...$args);
    }

    /**
     * @since 17xxxx Session utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Session::start()
     */
    public static function startSession(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Session->start(...$args);
    }
}
