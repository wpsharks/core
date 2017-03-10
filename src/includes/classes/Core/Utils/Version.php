<?php
/**
 * Version utilities.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Version utilities.
 *
 * @since 150424 Initial release.
 */
class Version extends Classes\Core\Base\Core implements Interfaces\VersionConstants
{
    /**
     * Is it a valid version?
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
        return (bool) preg_match($this::VERSION_REGEX_VALID, $version);
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
    public function isValidDev(string $version): bool
    {
        if (!$version) {
            return false; // Nope.
        }
        return (bool) preg_match($this::VERSION_REGEX_VALID_DEV, $version);
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
    public function isValidStable(string $version): bool
    {
        if (!$version) {
            return false; // Nope.
        }
        return (bool) preg_match($this::VERSION_REGEX_VALID_STABLE, $version);
    }
}
