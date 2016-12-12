<?php
/**
 * ZenHub.
 *
 * @author @jaswsinc
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
 * ZenHub.
 *
 * @since 16xxxx
 */
trait ZenHub
{
    /**
     * @since 16xxxx ZenHub utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\ZenHub::getJson()
     */
    public static function zenHubGetJson(...$args)
    {
        return $GLOBALS[static::class]->Utils->©ZenHub->getJson(...$args);
    }
}
