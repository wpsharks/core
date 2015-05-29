<?php
namespace WebSharks\Core\Classes\Utils;

/**
 * Version utilities.
 *
 * @since 150424 Initial release.
 */
class VersionUtils extends AbsBase
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
     * Is it a valid version?
     *
     * @since 150424 Initial release.
     *
     * @param string $version Input version.
     *
     * @return bool `TRUE` if valid.
     */
    public function versionIsValid($version)
    {
        if (!($version = (string) $version)) {
            return false; // Nope.
        }
        return (boolean) preg_match($this->def_regex_valid_version, $version);
    }

    /**
     * Is it a valid dev version?
     *
     * @since 150424 Initial release.
     *
     * @param string $version Input version.
     *
     * @return bool `TRUE` if valid.
     */
    public function versionIsValidDev($version)
    {
        if (!($version = (string) $version)) {
            return false; // Nope.
        }
        return (boolean) preg_match($this->def_regex_valid_dev_version, $version);
    }

    /**
     * Is it a valid stable version?
     *
     * @since 150424 Initial release.
     *
     * @param string $version Input version.
     *
     * @return bool `TRUE` if valid.
     */
    public function versionIsValidStable($version)
    {
        if (!($version = (string) $version)) {
            return false; // Nope.
        }
        return (boolean) preg_match($this->def_regex_valid_stable_version, $version);
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
    public function versionWsIsValid($version)
    {
        if (!($version = (string) $version)) {
            return false; // Nope.
        }
        return (boolean) preg_match($this->def_regex_valid_ws_version, $version);
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
    public function versionWsIsValidDev($version)
    {
        if (!($version = (string) $version)) {
            return false; // Nope.
        }
        return (boolean) preg_match($this->def_regex_valid_ws_dev_version, $version);
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
    public function versionWsIsValidStable($version)
    {
        if (!($version = (string) $version)) {
            return false; // Nope.
        }
        return (boolean) preg_match($this->def_regex_valid_ws_stable_version, $version);
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
    public function versionWsTime($version)
    {
        if (!($version = (string) $version)) {
            return 0; // Not possible.
        }
        if (!$this->versionWsIsValid($version)) {
            return 0; // Not a valid version.
        }
        $Y = substr(date('Y'), 0, 2).substr($version, 0, 2);
        $m = substr($version, 2, 2); // Month.
        $d = substr($version, 4, 2); // Day.

        return strtotime($Y.'-'.$m.'-'.$d);
    }
}
