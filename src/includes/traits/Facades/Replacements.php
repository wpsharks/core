<?php
/**
 * Replacements.
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
 * Replacements.
 *
 * @since 151214
 */
trait Replacements
{
    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\ReplaceCodes::__invoke()
     */
    public static function fillReplacementCodes(...$args)
    {
        return $GLOBALS[static::class]->Utils->©ReplaceCodes->__invoke(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\ReplaceOnce::__invoke()
     */
    public static function strReplaceOnce(...$args)
    {
        return $GLOBALS[static::class]->Utils->©ReplaceOnce->__invoke(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\ReplaceOnce::i()
     */
    public static function strIReplaceOnce(...$args)
    {
        return $GLOBALS[static::class]->Utils->©ReplaceOnce->i(...$args);
    }
}
