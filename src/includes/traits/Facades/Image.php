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
     * @since 17xxxx SVG converter.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Image::svgToPng()
     */
    public static function svgToPng(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Image->svgToPng(...$args);
    }

    /**
     * @since 161010 PNG compression.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Image::compressPng()
     */
    public static function compressPng(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Image->compressPng(...$args);
    }

    /**
     * @since 17xxxx Geo-patterns.
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
     * @since 17xxxx Geo-patterns.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Image::geoPatternThumbnail()
     */
    public static function geoPatternThumbnail(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Image->geoPatternThumbnail(...$args);
    }
}
