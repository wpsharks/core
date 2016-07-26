<?php
/**
 * Email.
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
 * Email.
 *
 * @since 151214
 */
trait Email
{
    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Email::__invoke()
     */
    public static function email(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Email->__invoke(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Email::isValid()
     */
    public static function isEmail(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Email->isValid(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Email::isRoleBased()
     */
    public static function isRoleBasedEmail(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Email->isRoleBased(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Email::parseAddresses()
     */
    public static function parseEmailAddresses(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Email->parseAddresses(...$args);
    }
}
