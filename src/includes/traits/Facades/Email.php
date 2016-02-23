<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;

trait Email
{
    /**
     * @since 151214 Adding functions.
     */
    public static function email(...$args)
    {
        return $GLOBALS[static::class]->Utils->Email->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function isEmail(...$args)
    {
        return $GLOBALS[static::class]->Utils->Email->isValid(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function isRoleBasedEmail(...$args)
    {
        return $GLOBALS[static::class]->Utils->Email->isRoleBased(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function parseEmailAddresses(...$args)
    {
        return $GLOBALS[static::class]->Utils->Email->parseAddresses(...$args);
    }
}
