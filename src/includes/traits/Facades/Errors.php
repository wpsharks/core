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

trait Errors
{
    /**
     * @since 160710 Error utils.
     */
    public static function error(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Error->__invoke(...$args);
    }

    /**
     * @since 160710 Error utils.
     */
    public static function isError(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Error->is(...$args);
    }
}
