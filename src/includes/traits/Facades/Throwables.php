<?php
/**
 * Throwables.
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
 * Throwables.
 *
 * @since 160711
 */
trait Throwables
{
    /**
     * @since 160711 First facades.
     */
    public static function setupThrowableHandler(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Throwables->handle(...$args);
    }
}
