<?php
/**
 * Keygen.
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
 * Keygen.
 *
 * @since 17xxxx Keygen.
 */
trait Keygen
{
    /**
     * @since 17xxxx Keygen.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Keygen::license()
     */
    public static function licenseKey(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Keygen->license(...$args);
    }

    /**
     * @since 17xxxx Keygen.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Keygen::publicApi()
     */
    public static function publicApiKey(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Keygen->publicApi(...$args);
    }

    /**
     * @since 17xxxx Keygen.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Keygen::secretApi()
     */
    public static function secretApiKey(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Keygen->secretApi(...$args);
    }

    /**
     * @since 17xxxx Keygen.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Keygen::secretSig()
     */
    public static function secretSigKey(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Keygen->secretSig(...$args);
    }
}
