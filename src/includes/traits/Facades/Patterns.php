<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;

trait Patterns
{
    /**
     * @since 151214 Adding functions.
     */
    public static function regexFrag(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RegexFrag->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function regexPatternIn(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RegexPattern->in(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function regexPatternsMatch(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RegexPatterns->match(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function wdRegex(...$args)
    {
        return $GLOBALS[static::class]->Utils->©WdRegex->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function wdRegexFrag(...$args)
    {
        return $GLOBALS[static::class]->Utils->©WdRegex->frag(...$args);
    }
}
