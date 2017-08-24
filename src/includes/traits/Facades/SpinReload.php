<?php
/**
 * Spin & reload.
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
 * Spin & reload.
 *
 * @since 170824.30708 Spin & reload.
 */
trait SpinReload
{
    /**
     * @since 170824.30708 Spin & reload.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\SpinReload::__invoke()
     */
    public static function spinReload(...$args)
    {
        return $GLOBALS[static::class]->Utils->©SpinReload->__invoke(...$args);
    }
}
