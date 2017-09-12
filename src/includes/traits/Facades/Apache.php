<?php
/**
 * Apache.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Apache.
 *
 * @since 160522
 */
trait Apache
{
    /**
     * @since 160522 Apache utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Apache::htaccessDeny()
     */
    public static function apacheHtaccessDeny(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Apache->htaccessDeny(...$args);
    }
}
