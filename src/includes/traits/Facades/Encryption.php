<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;

trait Encryption
{
    /**
     * @since 151214 Adding functions.
     */
    public static function randomKey(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RandomKey->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function passwordStrength(...$args)
    {
        return $GLOBALS[static::class]->Utils->©PwStrength->__invoke(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function passwordSha256(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Password->sha256(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function encrypt(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Rijndael256->encrypt(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function decrypt(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Rijndael256->decrypt(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function sha256KeyedHash(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Sha256->keyedHash(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function hashIds(...$args)
    {
        return $GLOBALS[static::class]->Utils->©HashIds->encode(...$args);
    }

    /**
     * @since 151214 Adding functions.
     */
    public static function decodeHashId(...$args)
    {
        return $GLOBALS[static::class]->Utils->©HashIds->decode(...$args);
    }
}
