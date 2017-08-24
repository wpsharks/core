<?php
/**
 * Fatalities.
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
 * Fatalities.
 *
 * @since 170824.30708 Fatalities
 */
trait Fatalities
{
    /**
     * @since 170824.30708 Fatalities
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Fatalities::die()
     */
    public static function die(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Fatalities->die(...$args);
    }

    /**
     * @since 170824.30708 Fatalities
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Fatalities::dieEcho()
     */
    public static function dieEcho(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Fatalities->dieEcho(...$args);
    }

    /**
     * @since 170824.30708 Fatalities
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Fatalities::dieInvalid()
     */
    public static function dieInvalid(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Fatalities->dieInvalid(...$args);
    }

    /**
     * @since 170824.30708 Fatalities
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Fatalities::dieForbidden()
     */
    public static function dieForbidden(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Fatalities->dieForbidden(...$args);
    }
}
