<?php
declare (strict_types = 1);
namespace WebSharks\Core\Interfaces;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function get_defined_vars as vars;

/**
 * Second constants.
 *
 * @since 160515 Second constants.
 */
interface SecondConstants
{
    /**
     * Seconds in minute.
     *
     * @since 160515 Second constants.
     *
     * @type int Seconds in minute.
     */
    const SECONDS_IN_MINUTE = 60;

    /**
     * Seconds in hour.
     *
     * @since 160515 Second constants.
     *
     * @type int Seconds in hour.
     */
    const SECONDS_IN_HOUR = 3600;

    /**
     * Seconds in day.
     *
     * @since 160515 Second constants.
     *
     * @type int Seconds in day.
     */
    const SECONDS_IN_DAY = 86400;

    /**
     * Seconds in week.
     *
     * @since 160515 Second constants.
     *
     * @type int Seconds in week.
     */
    const SECONDS_IN_WEEK = 604800;

    /**
     * Seconds in year.
     *
     * @since 160515 Second constants.
     *
     * @type int Seconds in year.
     */
    const SECONDS_IN_YEAR = 31536000;
}
