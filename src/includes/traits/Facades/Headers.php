<?php
/**
 * Headers.
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
 * Headers.
 *
 * @since 151214
 */
trait Headers
{
    /**
     * @since 151214 First facades.
     */
    public static function statusHeader(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Headers->sendStatus(...$args);
    }

    /**
     * @since 160118 Adding no-cache headers.
     */
    public static function noCacheHeaders(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Headers->sendNoCache(...$args);
    }

    /**
     * @since 151214 First facades.
     */
    public static function parseHeaders(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Headers->parse(...$args);
    }
}
