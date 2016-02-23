<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;

trait Bitly
{
    /**
     * @since 160102 Adding bitly.
     */
    public static function bitlyShorten(...$args)
    {
        return $GLOBALS[static::class]->Utils->Bitly->shorten(...$args);
    }

    /**
     * @since 160114 Bitly link history.
     */
    public static function bitlyLinkHistory(...$args)
    {
        return $GLOBALS[static::class]->Utils->Bitly->linkHistory(...$args);
    }
}
