<?php
/**
 * Backtrace utilities.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Backtrace utilities.
 *
 * @since 17xxxx Debugging utilities.
 */
class Backtrace extends Classes\Core\Base\Core
{
    /**
     * Get backtrace callers.
     *
     * @since 17xxxx Debugging utilities.
     *
     * @param int  $at  Starting at position.
     * @param bool $all Include magic middle-men?
     *
     * @return array Backtrace callers.
     */
    public function callers(int $at = 0, bool $all = false): array
    {
        $callers = []; // Initialize.

        // `[0]` is the call to this function.
        // So we start searching from index `1`.
        // An additional `$at` location is added to this.

        if (!($backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS))) {
            return $callers; // Not possible; unexpected trace.
        }
        for ($_i = 1 + $at; $_i < count($backtrace); ++$_i) {
            $_caller   = $backtrace[$_i];
            $_function = $_caller['function'] ?? '';
            $_class    = $_caller['class'] ?? '';
            $_type     = $_caller['type'] ?? '';

            if (!$all) { // If not `$all`, exclude magicals, facades, and other middle-men.
                if ($_class && mb_strpos($_function, '__') === 0 && isset($_function[2]) && $_function[2] !== '_') {
                    continue; // Bypass magic (middle-man) methods in classes.
                } elseif ($_class && $_type === '::' && mb_stripos($_class, '\\Facades') !== false) {
                    continue; // Bypass facades that sit in the middle also.
                }
            }
            $callers[] = ($_class && $_type ? $_class.$_type : '').$_function;
            // e.g., `a->method`, `b::method`, `function`, `__`, etc.
        } // unset($_caller, $_function, $_class, $_type); // Housekeeping.

        return $callers;
    }

    /**
     * Has backtrace callers?
     *
     * @since 17xxxx Debugging utilities.
     *
     * @param string|array $caller Caller(s).
     * @param int          $at     Starting at position.
     * @param bool         $all    Include magic middle-men?
     *
     * @return bool True if has backtrace caller(s).
     *
     * @note If `$caller` is an array, the order of the given array matters.
     * i.e., Callers must exist in a matching order as given by the `$caller` param.
     */
    public function hasCaller($caller, int $at = 0, bool $all = false): bool
    {
        $caller  = (array) $caller;
        $callers = $this->callers(1 + $at, $all);

        $caller  = implode('.', $caller).'.';
        $callers = implode('.', $callers).'.';

        return mb_stripos($callers, $caller) === 0;
    }
}
