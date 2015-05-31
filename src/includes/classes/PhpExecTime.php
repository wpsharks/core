<?php
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
     * @param null|int $max Seconds max; to set max execution time.
     *
     * @return int Max execution time; in seconds.
     */
    public function max($max = null)
    {
        if (isset($max) && ($max = (integer) $max) >= 0) {
            @set_time_limit($max);
        }
        return (integer) ini_get('max_execution_time');
    }
}
