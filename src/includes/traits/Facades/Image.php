<?php
/**
 * Image.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
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
}
