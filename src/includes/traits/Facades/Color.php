<?php
/**
 * Color.
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
 * Color.
 *
 * @since 161010
 */
trait Color
{
    /**
     * @since 161012 Color utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Color::sha1()
     */
    public static function sha1Color(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Color->sha1(...$args);
    }

    /**
     * @since 161012 Color utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Color::adjustLuminosity()
     */
    public static function adjustColorLuminosity(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Color->adjustLuminosity(...$args);
    }

    /**
     * @since 161012 Color utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Color::contrastingBw()
     */
    public static function contrastingBwColor(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Color->contrastingBw(...$args);
    }

    /**
     * @since 161012 Color utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Color::cleanHex()
     */
    public static function cleanHexColor(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Color->cleanHex(...$args);
    }
}
