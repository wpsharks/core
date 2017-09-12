<?php
/**
 * Indents.
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
 * Indents.
 *
 * @since 160720
 */
trait Indents
{
    /**
     * @since 160720 Indent utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Indents::stripLeading()
     */
    public static function stripLeadingIndents(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Indents->stripLeading(...$args);
    }
}
