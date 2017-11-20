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
     * @see Classes\Core\Utils\Image::identipattern()
     */
    public static function identipattern(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Image->identipattern(...$args);
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
     * @since 17xxxx Imagick utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Image::onePx()
     */
    public static function onePx(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Image->onePx(...$args);
    }

    /**
     * @since 17xxxx Imagick utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Image::extToFormat()
     */
    public static function imageExtToFormat(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Image->extToFormat(...$args);
    }

    /**
     * @since 17xxxx Imagick utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Image::formatToExt()
     */
    public static function imageFormatToExt(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Image->formatToExt(...$args);
    }

    /**
     * @since 17xxxx Imagick utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Image::extToMimeType()
     */
    public static function imageExtToMimeType(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Image->extToMimeType(...$args);
    }

    /**
     * @since 17xxxx Imagick utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Image::formatToMimeType()
     */
    public static function imageFormatToMimeType(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Image->formatToMimeType(...$args);
    }
}
