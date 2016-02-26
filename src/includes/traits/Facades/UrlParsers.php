<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;

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
