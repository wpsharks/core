<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

trait Markdown
{
    /**
     * @since 151214 Adding functions.
     */
    public static function markdown(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Markdown->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function stripMarkdown(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Markdown->strip(...$args);
    }
}
