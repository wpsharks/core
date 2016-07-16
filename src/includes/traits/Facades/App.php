<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

trait App
{
    /**
     * @since 160102 App.
     */
    public static function app(...$args)
    {
        return $GLOBALS[static::class];
    }

    /**
     * @since 160715 App parent.
     *
     * @param int $levels Up X levels.
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
     */
    public static function appCore()
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
