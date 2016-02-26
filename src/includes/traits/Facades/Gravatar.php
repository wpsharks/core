<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;

trait Gravatar
{
    /**
     * @since 160114 Gravatars.
     */
    public static function gravatarUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->Gravatar->url(...$args);
    }
}
