<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;

trait Arrays
{
    /**
     * @since 160102 App.
     */
    public static function arrayUnshiftAssoc(&$array, $key, $value)
    {
        return $GLOBALS[static::class]->Utils->©UnshiftAssoc->__invoke($array, $key, $value);
    }
}
