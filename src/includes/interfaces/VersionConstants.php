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
 * Version-related constants.
 *
 * @since 150424 Initial release.
 */
interface VersionConstants
{
    /**
     * PHP version string validation pattern.
     *
     * @since 150424 Initial release.
     *
     * @type string PHP version string validation pattern.
     *
     * @note Requirements are as follows:
     *
     *       1. Must follow {@link http://semver.org semantic versioning guidelines}.
     *          However, a development-state suffix does not have to start with a `-` if the first character is a letter; e.g., 1.1RC is fine.
     */
    const VERSION_REGEX_VALID = '/^'.
        '(?:[0-9](?:[0-9]|\.(?!\.))*[0-9]|[0-9])'.
        '(?:\-?(?:[a-zA-Z0-9](?:[a-zA-Z0-9]|[.\-](?![.\-]))*[a-zA-Z0-9]|[a-zA-Z0-9]))?'.
        '(?:\+(?:[a-zA-Z0-9](?:[a-zA-Z0-9]|[.\-](?![.\-]))*[a-zA-Z0-9]|[a-zA-Z0-9]))?'.
    '$/u';

    /**
     * PHP dev version string validation pattern.
     *
     * @since 150424 Initial release.
     *
     * @type string PHP dev version string validation pattern.
     *
     * @note Requirements are as follows:
     *
     *       1. Must follow {@link http://semver.org semantic versioning guidelines}.
     *          However, a development-state suffix does not have to start with a `-` if the first character is a letter; e.g., 1.1RC is fine.
     *       2. Must have a development-state suffix; perhaps followed by an optional build suffix.
     */
    const VERSION_REGEX_VALID_DEV = '/^'.
        '(?:[0-9](?:[0-9]|\.(?!\.))*[0-9]|[0-9])'.
        '(?:\-?(?:[a-zA-Z0-9](?:[a-zA-Z0-9]|[.\-](?![.\-]))*[a-zA-Z0-9]|[a-zA-Z0-9]))'.
        '(?:\+(?:[a-zA-Z0-9](?:[a-zA-Z0-9]|[.\-](?![.\-]))*[a-zA-Z0-9]|[a-zA-Z0-9]))?'.
    '$/u';

    /**
     * PHP stable version string validation pattern.
     *
     * @since 150424 Initial release.
     *
     * @type string PHP stable version string validation pattern.
     *
     * @note Requirements are as follows:
     *
     *       1. Must follow {@link http://semver.org semantic versioning guidelines}.
     *          However, a development-state suffix does not have to start with a `-` if the first character is a letter; e.g., 1.1RC is fine.
     *       2. Must not contain a development-state suffix (i.e., it must be a stable version).
     *             However, it may contain an optional build suffix; and still be stable.
     */
    const VERSION_REGEX_VALID_STABLE = '/^'.
        '(?:[0-9](?:[0-9]|\.(?!\.))*[0-9]|[0-9])'.
        '(?:\+(?:[a-zA-Z0-9](?:[a-zA-Z0-9]|[.\-](?![.\-]))*[a-zA-Z0-9]|[a-zA-Z0-9]))?'.
    '$/u';
}
