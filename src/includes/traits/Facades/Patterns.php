<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;

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
    public static function wregx(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Wregx->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function wregxFrag(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Wregx->frag(...$args);
    }
}
