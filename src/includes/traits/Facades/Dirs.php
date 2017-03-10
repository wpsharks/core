<?php
/**
 * Dirs.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Dirs.
 *
 * @since 151214
 */
trait Dirs
{
    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\DirTmp::__invoke()
     */
    public static function tmpDir(...$args)
    {
        return $GLOBALS[static::class]->Utils->©DirTmp->__invoke(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\DirPath::normalize()
     */
    public static function normalizeDirPath(...$args)
    {
        return $GLOBALS[static::class]->Utils->©DirPath->normalize(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\DirDelete::__invoke()
     */
    public static function deleteDir(...$args)
    {
        return $GLOBALS[static::class]->Utils->©DirDelete->__invoke(...$args);
    }
}
