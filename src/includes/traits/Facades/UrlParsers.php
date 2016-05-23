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

trait UrlParsers
{
    /**
     * @since 151214 Adding functions.
     */
    public static function parseUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlParse->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function unparseUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlParse->un(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function parseUrlHost(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlHost->parse(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function unparseUrlHost(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UrlHost->unParse(...$args);
    }
}
