<?php
/**
 * Password utils.
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
 * Password utils.
 *
 * @since 17xxxx
 */
trait Password
{
    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Password::strength()
     */
    public static function passwordStrength(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Password->strength(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Password::sha256()
     */
    public static function passwordSha256(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Password->sha256(...$args);
    }
}
