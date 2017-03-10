<?php
/**
 * No-cache.
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
 * No-cache.
 *
 * @since 160606
 */
trait NoCache
{
    /**
     * @since 160606 No-cache utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\NoCache::setFlags()
     */
    public static function noCacheFlags(...$args)
    {
        return $GLOBALS[static::class]->Utils->©NoCache->setFlags(...$args);
    }
}
