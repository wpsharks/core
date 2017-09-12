<?php
/**
 * Base64.
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
 * Base64.
 *
 * @since 151214
 */
trait Base64
{
    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Base64::urlSafeEncode()
     */
    public static function base64UrlSafeEncode(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Base64->urlSafeEncode(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Base64::urlSafeDecode()
     */
    public static function base64UrlSafeDecode(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Base64->urlSafeDecode(...$args);
    }
}
