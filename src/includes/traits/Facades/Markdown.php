<?php
/**
 * Markdown.
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
 * Markdown.
 *
 * @since 151214
 */
trait Markdown
{
    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Markdown::__invoke()
     */
    public static function markdown(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Markdown->__invoke(...$args);
    }

    /**
     * @since 16xxxx Markdown utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Markdown::firstImageUrl()
     */
    public static function firstImageUrlInMarkdown(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Markdown->firstImageUrl(...$args);
    }

    /**
     * @since 151214 First facades.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Markdown::strip()
     */
    public static function stripMarkdown(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Markdown->strip(...$args);
    }
}
