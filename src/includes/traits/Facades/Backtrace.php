<?php
/**
 * Backtrace.
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
 * Backtrace.
 *
 * @since 170126.77069
 */
trait Backtrace
{
    /**
     * @since 170126.77069 Backtrace utils.
     *
     * @param int  $at  Starting at position.
     * @param bool $all Include magic middle-men?
     *
     * @see Classes\Core\Utils\Backtrace::callers()
     */
    public static function backtraceCallers(int $at = 0, bool $all = false)
    {
        return $GLOBALS[static::class]->Utils->©Backtrace->callers(1 + $at, $all);
    }

    /**
     * @since 170126.77069 Backtrace utils.
     *
     * @param string|array $caller Caller(s).
     * @param int          $at     Starting at position.
     * @param bool         $all    Include magic middle-men?
     *
     * @see Classes\Core\Utils\Backtrace::hasCaller()
     */
    public static function hasBacktraceCaller($caller, int $at = 0, bool $all = false)
    {
        return $GLOBALS[static::class]->Utils->©Backtrace->hasCaller($caller, 1 + $at, $all);
    }
}
