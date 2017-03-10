<?php
/**
 * Time utilities.
 *
 * @author @jaswrks
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
 * Time utilities.
 *
 * @since 170124.74961 Initial release.
 */
class Time extends Classes\Core\Base\Core implements Interfaces\SecondConstants
{
    /**
     * Human time difference.
     *
     * @since 170124.74961 Initial release.
     *
     * @param int      $from   From time.
     * @param int|null $to     Defaults to current time.
     * @param string   $format Formatting option.
     *
     * @return string Human time difference.
     */
    public function humanDiff(int $from, int $to = null, string $format = ''): string
    {
        $to   = $to ?? time();
        $diff = (int) abs($to - $from);

        if ($format !== 'abbrev' && $format !== 'default') {
            $format = 'default'; // Must be valid.
        }
        if ($diff < $this::SECONDS_IN_MINUTE) {
            $diff            = max(1, $diff);
            $fmat['abbrev']  = ['%1$ss', '%1$ss'];
            $fmat['default'] = ['%1$s sec', '%1$s secs'];
        } elseif ($diff < $this::SECONDS_IN_HOUR) {
            $diff            = max(1, round($diff / $this::SECONDS_IN_MINUTE));
            $fmat['abbrev']  = ['%1$sm', '%1$sm'];
            $fmat['default'] = ['%1$s min', '%1$s mins'];
        } elseif ($diff < $this::SECONDS_IN_DAY) {
            $diff            = max(1, round($diff / $this::SECONDS_IN_HOUR));
            $fmat['abbrev']  = ['%1$sh', '%1$sh'];
            $fmat['default'] = ['%1$s hour', '%1$s hours'];
        } elseif ($diff < $this::SECONDS_IN_WEEK) {
            $diff            = max(1, round($diff / $this::SECONDS_IN_DAY));
            $fmat['abbrev']  = ['%1$sd', '%1$sd'];
            $fmat['default'] = ['%1$s day', '%1$s days'];
        } elseif ($diff < $this::SECONDS_IN_WEEK * 4) {
            $diff            = max(1, round($diff / $this::SECONDS_IN_WEEK));
            $fmat['abbrev']  = ['%1$sw', '%1$sw'];
            $fmat['default'] = ['%1$s week', '%1$s weeks'];
        } elseif ($diff < $this::SECONDS_IN_YEAR) {
            $diff            = max(1, round($diff / ($this::SECONDS_IN_WEEK * 4)));
            $fmat['abbrev']  = ['%1$smo', '%1$smo'];
            $fmat['default'] = ['%1$s month', '%1$s months'];
        } else {
            $diff            = max(1, round($diff / $this::SECONDS_IN_YEAR));
            $fmat['abbrev']  = ['%1$sy', '%1$sy'];
            $fmat['default'] = ['%1$s year', '%1$s years'];
        }
        return sprintf(_n($fmat[$format][0], $fmat[$format][1], $diff), $diff);
    }
}
