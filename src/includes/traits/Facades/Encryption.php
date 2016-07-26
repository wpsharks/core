<?php
/**
 * Encryption.
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
 * Encryption.
 *
 * @since 160701
 */
trait Encryption
{
    /**
     * @since 160701 Unique ID.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\UniqueId::__invoke()
     */
    public static function uniqueId(...$args)
    {
        return $GLOBALS[static::class]->Utils->©UniqueId->__invoke(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\RandomKey::__invoke()
     */
    public static function randomKey(...$args)
    {
        return $GLOBALS[static::class]->Utils->©RandomKey->__invoke(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\PwStrength::__invoke()
     */
    public static function passwordStrength(...$args)
    {
        return $GLOBALS[static::class]->Utils->©PwStrength->__invoke(...$args);
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

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Rijndael256::encrypt()
     */
    public static function encrypt(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Rijndael256->encrypt(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Rijndael256::decrypt()
     */
    public static function decrypt(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Rijndael256->decrypt(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Sha256::keyedHash()
     */
    public static function sha256KeyedHash(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Sha256->keyedHash(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\HashIds::encode()
     */
    public static function hashIds(...$args)
    {
        return $GLOBALS[static::class]->Utils->©HashIds->encode(...$args);
    }

    /**
     * @since 160630 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\HashIds::decode()
     */
    public static function decodeHashedIds(...$args)
    {
        return $GLOBALS[static::class]->Utils->©HashIds->decode(...$args);
    }
}
