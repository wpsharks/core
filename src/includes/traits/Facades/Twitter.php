<?php
/**
 * Twitter utils.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
//
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
//
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Twitter utils.
 *
 * @since 17xxxx
 */
trait Twitter
{
    /**
     * @since 17xxxx Twitter utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Twitter::getRemote()
     */
    public static function twitterGetRemote(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Twitter->getRemote(...$args);
    }

    /**
     * @since 17xxxx Twitter utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Twitter::getJson()
     */
    public static function twitterGetJson(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Twitter->getJson(...$args);
    }
}
