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
 * @since 170211.63148 Debugging utilities.
 */
class Backtrace extends Classes\Core\Base\Core
{
    /**
     * Get backtrace callers.
     *
     * @since 170211.63148 Debugging utilities.
     *
     * @param int|null $at        Starting at position.
     * @param bool     $all       Include magic middle-men?
     * @param int      $___offset For internal use only.
     *
     * @return array Backtrace callers.
     */
    public function callers(int $at = null, bool $all = false, $___offset = 0): array
    {
        $callers = []; // Initialize.

        // `[0]` is the call to this function.
        // So we start searching from offset index `1`.
        // An additional `$___offset` location is added to this.
        // And then an additional `$at` location can be added also.

        if (!($backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS))) {
            return $callers; // Not possible; unexpected trace.
        }
        for ($_i = 1 + $___offset + $at; $_i < count($backtrace); ++$_i) {
            $_caller   = $backtrace[$_i];
            $_function = $_caller['function'] ?? '';
            $_class    = $_caller['class'] ?? '';
            $_type     = $_caller['type'] ?? '';

            if (!$all) { // If not `$all`, exclude magicals, facades, and other middle-men.
                if ($_class && mb_strpos($_function, '__') === 0 && isset($_function[2]) && $_function[2] !== '_') {
                    continue; // Bypass magic (middle-man) methods in classes.
                    //
                } elseif ($_class && $_type === '::' && mb_stripos($_class, '\\Facades') !== false) {
                    continue; // Bypass facades that sit in the middle also.
                    //
                } elseif ($_class && mb_strtolower($_class) === 'wp_hook') {
                    continue; // Bypass everything in `WP_Hook` class.
                    //
                } elseif (!$_class) { // A few others.
                    $_lc_function = mb_strtolower($_function);

                    if (in_array($_lc_function, ['call_user_func', 'call_user_func_array'], true)) {
                        continue; // Bypass these also.
                    } elseif (in_array($_lc_function, ['do_action', 'apply_filters'], true)) {
                        continue; // Bypass these also.
                    }
                } // This covers WordPress and the WP Sharks Core also.
            }
            $callers[] = ($_class && $_type ? $_class.$_type : '').$_function;
            // e.g., `a->method`, `b::method`, `function`, `__`, etc.
        } // unset($_caller, $_function, $_lc_function, $_class, $_type); // Housekeeping.

        return $callers;
    }

    /**
     * Has backtrace callers?
     *
     * @since 170211.63148 Debugging utilities.
     *
     * @param string|array $caller    Caller(s).
     * @param int|null     $at        Starting at position.
     * @param bool         $all       Include magic middle-men?
     * @param int          $___offset For internal use only.
     *
     * @return bool True if has backtrace caller(s).
     *
     * @note If `$caller` is an array, the order of the given array matters.
     * i.e., Callers must exist in a matching order as given by the `$caller` param.
     *
     * @note If `$at` is set (even if `0`), then position matters too.
     * i.e., Caller(s) must exist starting at the given position.
     * Conversely, if `$at` is not set, the position does not matter.
     */
    public function hasCaller($caller, int $at = null, bool $all = false, $___offset = 0): bool
    {
        if (!$caller) {
            return false;
        }
        $caller  = (array) $caller;
        $caller  = array_map('strval', $caller);
        $callers = $this->callers($at, $all, 1 + $___offset);

        $caller  = '.'.implode('.', $caller).'.';
        $callers = '.'.implode('.', $callers).'.';

        if (isset($at)) { // Position matters.
            return mb_stripos($callers, $caller) === 0;
        }
        return mb_stripos($callers, $caller) !== false;
    }
}
