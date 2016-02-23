<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;

trait Percentages
{
    /**
     * @since 151214 Adding functions.
     */
    public static function percentDiff(...$args)
    {
        return $GLOBALS[static::class]->Utils->Percent->diff(...$args);
    }
}
