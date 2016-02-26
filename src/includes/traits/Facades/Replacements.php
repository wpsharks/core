<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;

trait Replacements
{
    /**
     * @since 151214 Adding functions.
     */
    public static function fillReplacementCodes(...$args)
    {
        return $GLOBALS[static::class]->Utils->©ReplaceCodes->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function strReplaceOnce(...$args)
    {
        return $GLOBALS[static::class]->Utils->©ReplaceOnce->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function strIReplaceOnce(...$args)
    {
        return $GLOBALS[static::class]->Utils->©ReplaceOnce->i(...$args);
    }
}
