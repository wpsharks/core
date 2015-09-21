<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Traits;

/**
 * Version utilities.
 *
 * @since 150424 Initial release.
 */
class Version extends AbsBase
{
    use Traits\VersionDefinitions;

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
    public function isValid($version)
    {
        if (!($version = (string) $version)) {
            return false; // Nope.
        }
        return (boolean) preg_match($this->DEF_VERSION_REGEX_VALID, $version);
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
    public function isValidDev($version)
    {
        if (!($version = (string) $version)) {
            return false; // Nope.
        }
        return (boolean) preg_match($this->DEF_VERSION_REGEX_VALID_DEV, $version);
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
    public function isValidStable($version)
    {
        if (!($version = (string) $version)) {
            return false; // Nope.
        }
        return (boolean) preg_match($this->DEF_VERSION_REGEX_VALID_STABLE, $version);
    }
}
