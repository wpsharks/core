<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;

trait Dump
{
    /**
     * @since 151214 Adding functions.
     */
    public static function dump(...$args)
    {
        return $GLOBALS[static::class]->Utils->Dump->__invoke(...$args);
    }
}
