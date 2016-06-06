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

trait RequestType
{
    /**
     * @since 160531 Request types.
     */
    public static function isAjax(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RequestType->isAjax(...$args);
    }

    /**
     * @since 160531 Request types.
     */
    public static function isApi(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RequestType->isApi(...$args);
    }

    /**
     * @since 160531 Request types.
     */
    public static function doingAction(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RequestType->doingAction(...$args);
    }
}
