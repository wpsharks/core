<?php
/**
 * WS version utilities.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
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
 * WS version utilities.
 *
 * @since 150424 Initial release.
 */
class WsVersion extends Classes\Core\Base\Core implements Interfaces\WsVersionConstants
{
    /**
     * Is it a valid WS version?
     *
     * @since 150424 Initial release.
     *
     * @param string $version Input version.
     *
     * @return bool `TRUE` if valid.
     */
    public function isValid(string $version): bool
    {
        if (!$version) {
            return false; // Nope.
        }
        return (bool) preg_match($this::WS_VERSION_REGEX_VALID, $version);
    }

    /**
     * Is it a valid WS dev version?
     *
     * @since 150424 Initial release.
     *
     * @param string $version Input version.
     *
     * @return bool `TRUE` if valid.
     */
    public function isValidDev(string $version): bool
    {
        if (!$version) {
            return false; // Nope.
        }
        return (bool) preg_match($this::WS_VERSION_REGEX_VALID_DEV, $version);
    }

    /**
     * Is it a valid WS stable version?
     *
     * @since 150424 Initial release.
     *
     * @param string $version Input version.
     *
     * @return bool `TRUE` if valid.
     */
    public function isValidStable(string $version): bool
    {
        if (!$version) {
            return false; // Nope.
        }
        return (bool) preg_match($this::WS_VERSION_REGEX_VALID_STABLE, $version);
    }

    /**
     * WS version to date.
     *
     * @since 150424 Initial release.
     *
     * @param string $version Input version.
     * @param string $format  Any valid date format string.
     *
     * @return string Date string, else empty string on failure.
     */
    public function date(string $version, string $format = 'F jS, Y'): string
    {
        if (!$format) {
            return ''; // Not possible.
        } elseif (!($time = $this->time($version))) {
            return ''; // Not possible.
        }
        return date($format, $time);
    }

    /**
     * WS version to time.
     *
     * @since 150424 Initial release.
     *
     * @param string $version Input version.
     *
     * @return int Timestamp, else `0` on failure.
     */
    public function time(string $version): int
    {
        if (!$version) {
            return 0; // Not possible.
        } elseif (!$this->isValid($version)) {
            return 0; // Invalid version.
        }
        $Y = substr(date('Y'), 0, 2).substr($version, 0, 2);
        $m = substr($version, 2, 2); // Month.
        $d = substr($version, 4, 2); // Day.

        $time = strtotime($Y.'-'.$m.'-'.$d.' 12:00 am');

        if (preg_match('/^[0-9]{6}\.([0-9]+)/u', $version, $_m)) {
            $time += $_m[1]; // Seconds into current day.
        } // unset($_m); // Housekeeping.

        return $time;
    }
}
