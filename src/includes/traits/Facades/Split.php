<?php
/**
 * Split.
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
 * Split.
 *
 * @since 170824.30708
 */
trait Split
{
    /**
     * @since 170824.30708 String splitter.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Split::__invoke()
     */
    public static function split(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Split->__invoke(...$args);
    }
}
