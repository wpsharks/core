<?php
/**
 * Colors.
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
 * Colors.
 *
 * @since 161010
 */
trait Colors
{
    /**
     * @since 161010 Color utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Colors::contrastingFg()
     */
    public static function contrastingFgColor(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Colors->contrastingFg(...$args);
    }
}
