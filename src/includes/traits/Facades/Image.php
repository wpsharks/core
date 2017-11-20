<?php
/**
 * Image.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
//
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
//
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Image.
 *
 * @since 161010
 */
trait Image
{
    /**
     * @since 17xxxx Imagick utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Image::geoPattern()
     */
    public static function geoPattern(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Image->geoPattern(...$args);
    }

    /**
     * @since 17xxxx Imagick utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Image::convert()
     */
    public static function convertImage(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Image->convert(...$args);
    }

    /**
     * @since 17xxxx Imagick utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Image::resize()
     */
    public static function resizeImage(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Image->resize(...$args);
    }

    /**
     * @since 17xxxx Imagick utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Image::texturize()
     */
    public static function texturizeImage(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Image->texturize(...$args);
    }

    /**
     * @since 17xxxx Imagick utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Image::compress()
     */
    public static function compressImage(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Image->compress(...$args);
    }

    /**
     * @since 161010 Decode image data URL.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Image::decodeDataUrl()
     */
    public static function decodeImageDataUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Image->decodeDataUrl(...$args);
    }

    /**
     * @since 17xxxx One pixel utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Image::onePx()
     */
    public static function onePx(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Image->onePx(...$args);
    }
}
