<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

trait Closures
{
    /**
     * @since 160712 Closure utils.
     */
    public static function serializeClosure(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Closure->toString(...$args);
    }

    /**
     * @since 160712 Closure utils.
     */
    public static function unserializeClosure(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Closure->fromString(...$args);
    }
}
