<?php
/**
 * Debugging.
 *
 * @author @jaswrks
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
 * Debugging.
 *
 * @since 160522
 */
trait Debugging
{
    /**
     * @since 160522 Debug utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Debugging::logIssue()
     */
    public static function issue(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Debugging->logIssue(...$args);
    }

    /**
     * @since 160522 Debug utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Debugging::logReview()
     */
    public static function review(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Debugging->logReview(...$args);
    }
}
