<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;

trait Exceptions
{
    /**
     * @since 151214 Adding functions.
     */
    public static function setupExceptionHandler(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Exceptions->handle(...$args);
    }
}
