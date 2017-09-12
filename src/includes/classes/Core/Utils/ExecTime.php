<?php
/**
 * Exec time utilities.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

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
 * Exec time utilities.
 *
 * @since 150424 Initial release.
 */
class ExecTime extends Classes\Core\Base\Core
{
    /**
     * Max execution time.
     *
     * @since 150424 Initial release.
     *
     * @param int|null $max Max execution time.
     *
     * @return int Max execution time; in seconds.
     */
    public function max(int $max = null): int
    {
        if (isset($max) && $max >= 0) {
            @set_time_limit($max);
        }
        return (int) ini_get('max_execution_time');
    }
}
