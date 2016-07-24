<?php
/**
 * Dump.
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
 * Dump.
 *
 * @since 151214
 */
trait Dump
{
    /**
     * @since 151214 First facades.
     */
    public static function dump(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Dump->__invoke(...$args);
    }
}
