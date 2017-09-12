<?php
/**
 * Response utils.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
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
 * Response utils.
 *
 * @since 17xxxx
 */
trait Response
{
    /**
     * @since 17xxxx Response utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Response::create()
     */
    public static function createResponse(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Response->create(...$args);
    }
}
