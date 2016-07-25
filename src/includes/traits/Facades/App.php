<?php
/**
 * Application.
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
 * Application.
 *
 * @since 160102
 */
trait App
{
    /**
     * @since 160102 App.
     *
     * @param mixed ...$args to underlying utility.
     *
     * @see Classes\App
     */
    public static function app(...$args)
    {
        return $GLOBALS[static::class];
    }

    /**
     * @since 160715 App parent.
     *
     * @param int $levels Up X levels.
     *
     * @return Classes\App|null Parent App; else `null`.
     */
    public static function appParent(int $levels = 1)
    {
        $levels        = max(1, $levels);
        $level_counter = 1; // Start from `1`.
        $Parent        = $GLOBALS[static::class]->Parent;

        while ($Parent && $level_counter < $levels) {
            $Parent = $Parent->Parent ?? null;
            ++$level_counter; // Counter.
        }
        return $Parent; // Possible `null`.
    }

    /**
     * @since 160715 App core.
     *
     * @return Classes\App Core.
     */
    public static function appCore(): Classes\App
    {
        if (!($Parent = $GLOBALS[static::class]->Parent)) {
            return $GLOBALS[static::class];
            //
        } else { // Find root core.
            while ($Parent->Parent) {
                $Parent = $Parent->Parent;
            }
            return $Parent;
        }
    }
}
