<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * PHP execution time.
 *
 * @since 150424 Initial release.
 */
class PhpExecTime extends Classes\AbsBase
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
