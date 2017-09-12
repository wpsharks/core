<?php
/**
 * Simple expressions.
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
 * Simple expressions.
 *
 * @since 160708
 */
trait SimpleExpressions
{
    /**
     * @since 160708 Simple expression utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\SimpleExpression::toPhp()
     */
    public static function simplePhpExpr(...$args)
    {
        return $GLOBALS[static::class]->Utils->©SimpleExpression->toPhp(...$args);
    }
}
