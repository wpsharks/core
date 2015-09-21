<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Traits;

/**
 * WS version utilities.
 *
 * @since 150424 Initial release.
 */
class WsVersion extends AbsBase
{
    use Traits\WsVersionDefinitions;

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
     * Is it a valid WS version?
     *
     * @since 150424 Initial release.
     *
     * @param string $version Input version.
     *
     * @return bool `TRUE` if valid.
     */
    public function isValid($version)
    {
        if (!($version = (string) $version)) {
            return false; // Nope.
        }
        return (boolean) preg_match($this->DEF_WS_VERSION_REGEX_VALID, $version);
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
    public function isValidDev($version)
    {
        if (!($version = (string) $version)) {
            return false; // Nope.
        }
        return (boolean) preg_match($this->DEF_WS_VERSION_REGEX_VALID_DEV, $version);
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
    public function isValidStable($version)
    {
        if (!($version = (string) $version)) {
            return false; // Nope.
        }
        return (boolean) preg_match($this->DEF_WS_VERSION_REGEX_VALID_STABLE, $version);
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
    public function time($version)
    {
        if (!($version = (string) $version)) {
            return 0; // Not possible.
        }
        if (!$this->isValid($version)) {
            return 0; // Not a valid version.
        }
        $Y = substr(date('Y'), 0, 2).substr($version, 0, 2);
        $m = substr($version, 2, 2); // Month.
        $d = substr($version, 4, 2); // Day.

        return strtotime($Y.'-'.$m.'-'.$d);
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
    public function date($version, $format = 'F jS, Y')
    {
        if (!($time = $this->time($version))) {
            return ''; // Not possible.
        }
        if (!($format = (string) $format)) {
            return ''; // Not possible.
        }
        return date($format, $time);
    }
}
