<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function get_defined_vars as vars;

trait WordPress
{
    /**
     * @since 160219 WP utils.
     */
    public static function isWordPress(...$args)
    {
        return $GLOBALS[static::class]->Utils->©WordPress->is(...$args);
    }
}
