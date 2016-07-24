<?php
/**
 * No-cache.
 *
 * @author @jaswsinc
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
     */
    public static function noCacheFlags(...$args)
    {
        return $GLOBALS[static::class]->Utils->©NoCache->setFlags(...$args);
    }
}
