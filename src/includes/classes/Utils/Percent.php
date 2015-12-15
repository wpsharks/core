<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Dicer\Di;
use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Percent utilities.
 *
 * @since 15xxxx Percentage utils.
 */
class Percent extends Classes\AbsBase
{
    /**
     * Calculates percentage difference.
     *
     * @param int|float $from          Calculate from (i.e., value then).
     * @param int|float $to            Calculate to (i.e., the value now).
     * @param int       $precision     Defaults to `0`; i.e., no decimal place.
     * @param bool      $format_string If true, formatted `+|-[percent]%`.
     *
     * @return int|float|string A float if `$precision` is passed, else an integer (default behavior).
     *                          If `$format_string`, converted to string format: `+|-[percent]%`.
     */
    public function diff(float $from, float $to, int $precision = 0, bool $format_string = false)
    {
        if (!$from) {
            ++$from;
            ++$to;
        } // Stop division by `0`.

        $precision = max(0, $precision);
        $percent   = ($to - $from) / $from * 100;

        if ($precision) {
            $percent = (float) number_format($percent, $precision, '.', '');
        } else {
            $percent = (int) $percent;
        }
        if ($format_string) { // e.g., add a prefix/suffix?
            $percent = $percent > 0 ? '+'.$percent.'%' : $percent.'%';
        }
        return $percent;
    }
}
