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
 * @since 17xxxx
 */
trait UserAgent
{
    /**
     * @since 17xxxx User-Agent utils.
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
