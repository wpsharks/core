<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;

trait OEmbed
{
    /**
     * @since 151214 Adding functions.
     */
    public static function oEmbed(...$args)
    {
        return $GLOBALS[static::class]->Utils->OEmbed->__invoke(...$args);
    }
}
