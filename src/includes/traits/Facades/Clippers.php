<?php
/**
 * Clippers.
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
 * Clippers.
 *
 * @since 151214
 */
trait Clippers
{
    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Clip::__invoke()
     */
    public static function clip(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Clip->__invoke(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\MidClip::__invoke()
     */
    public static function midClip(...$args)
    {
        return $GLOBALS[static::class]->Utils->©MidClip->__invoke(...$args);
    }
}
