<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function get_defined_vars as vars;

trait Dirs
{
    /**
     * @since 151214 Adding functions.
     */
    public static function tmpDir(...$args)
    {
        return $GLOBALS[static::class]->Utils->©DirTmp->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function normalizeDirPath(...$args)
    {
        return $GLOBALS[static::class]->Utils->©DirPath->normalize(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function deleteDir(...$args)
    {
        return $GLOBALS[static::class]->Utils->©DirDelete->__invoke(...$args);
    }
}
