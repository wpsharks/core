<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;

trait Eols
{
    /**
     * @since 151214 Adding functions.
     */
    public static function normalizeEols(...$args)
    {
        return $GLOBALS[static::class]->Utils->Eols->normalize(...$args);
    }
}
