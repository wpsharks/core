<?php
/**
 * WebPurify.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
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
 * WebPurify.
 *
 * @since 151214
 */
trait WebPurify
{
    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\WebPurify::check()
     */
    public static function containsBadWords(...$args)
    {
        return $GLOBALS[static::class]->Utils->©WebPurify->check(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\WebPurify::checkSlug()
     */
    public static function slugContainsBadWords(...$args)
    {
        return $GLOBALS[static::class]->Utils->©WebPurify->checkSlug(...$args);
    }
}
