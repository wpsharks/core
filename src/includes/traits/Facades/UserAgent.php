<?php
/**
 * User-Agent.
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
 * User-Agent.
 *
 * @since 170413.34876
 */
trait UserAgent
{
    /**
     * @since 170413.34876 User-Agent utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UserAgent::isEngine()
     */
    public static function uaIsEngine(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UserAgent->isEngine(...$args);
    }
}
