<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;

trait Apache
{
    /**
     * @since 160522 Apache utilities.
     */
    public static function apacheHtaccessDeny(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Apache->htaccessDeny(...$args);
    }
}
