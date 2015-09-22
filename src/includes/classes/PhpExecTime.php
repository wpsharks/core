<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * PHP execution time.
 *
 * @since 150424 Initial release.
 */
class PhpExecTime extends AbsBase
{
    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Max execution time.
     *
     * @since 150424 Initial release.
     *
     * @param int|null $max Seconds max; to set max execution time.
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
