<?php
/**
 * Request utils.
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
 * Request utils.
 *
 * @since 17xxxx
 */
trait Request
{
    /**
     * @since 17xxxx Request utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Request::jsonData()
     */
    public static function jsonRequestData(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Request->jsonData(...$args);
    }
}
