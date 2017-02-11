<?php
/**
 * SRIs.
 *
 * @author @jaswsinc
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
 * SRIs.
 *
 * @since 170211.63148
 */
trait Sris
{
    /**
     * @since 170211.63148 SRI utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Sri::__invoke()
     */
    public static function sri(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Sri->__invoke(...$args);
    }
}
