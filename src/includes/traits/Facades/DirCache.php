<?php
/**
 * Dir cache utils.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Dir cache utils.
 *
 * @since 170824.30708 Dir cache utils.
 */
trait DirCache
{
    /**
     * @since 170824.30708 Dir cache utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\DirCache::get()
     */
    public static function dirCacheGet(...$args)
    {
        return $GLOBALS[static::class]->Utils->©DirCache->get(...$args);
    }

    /**
     * @since 170824.30708 Dir cache utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\DirCache::set()
     */
    public static function dirCacheSet(...$args)
    {
        return $GLOBALS[static::class]->Utils->©DirCache->set(...$args);
    }

    /**
     * @since 170824.30708 Dir cache utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\DirCache::clear()
     */
    public static function dirCacheClear(...$args)
    {
        return $GLOBALS[static::class]->Utils->©DirCache->clear(...$args);
    }
}
