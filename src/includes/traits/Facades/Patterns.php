<?php
/**
 * Patterns.
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
 * Patterns.
 *
 * @since 151214
 */
trait Patterns
{
    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\RegexFrag::__invoke()
     */
    public static function regexFrag(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RegexFrag->__invoke(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\RegexPattern::in()
     */
    public static function regexPatternIn(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RegexPattern->in(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\RegexPatterns::match()
     */
    public static function regexPatternsMatch(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RegexPatterns->match(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\WRegx::__invoke()
     */
    public static function wRegx(...$args)
    {
        return $GLOBALS[static::class]->Utils->©WRegx->__invoke(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\WRegx::frag()
     */
    public static function wRegxFrag(...$args)
    {
        return $GLOBALS[static::class]->Utils->©WRegx->frag(...$args);
    }

    /**
     * @since 160428 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\WRegx::bracket()
     */
    public static function wRegxBracket(...$args)
    {
        return $GLOBALS[static::class]->Utils->©WRegx->bracket(...$args);
    }

    /**
     * @since 160428 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\WRegx::urlToUriPattern()
     */
    public static function urlToWRegxUriPattern(...$args)
    {
        return $GLOBALS[static::class]->Utils->©WRegx->urlToUriPattern(...$args);
    }
}
