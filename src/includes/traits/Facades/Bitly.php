<?php
/**
 * Bitly.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Bitly.
 *
 * @since 160102
 */
trait Bitly
{
    /**
     * @since 160102 Adding bitly.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Bitly::shorten()
     */
    public static function bitlyShorten(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Bitly->shorten(...$args);
    }

    /**
     * @since 160114 Bitly link history.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Bitly::linkHistory()
     */
    public static function bitlyLinkHistory(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Bitly->linkHistory(...$args);
    }
}
