<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;

trait Wordpress
{
    /**
     * @since 160219 WP utils.
     */
    public static function isWordpress(...$args)
    {
        return $GLOBALS[static::class]->Utils->©WordPress->is(...$args);
    }
}
