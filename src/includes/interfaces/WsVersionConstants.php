<?php
/**
 * WS version-related constants.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Interfaces;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * WS version-related constants.
 *
 * @since 150424 Initial release.
 */
interface WsVersionConstants
{
    /**
     * Plugin version string validation pattern.
     *
     * @since 150424 Initial release.
     *
     * @var string Plugin version string validation pattern.
     *             This has additional limitations (but still compatible w/ PHP version strings).
     *
     * @internal Requirements are as follows:
     *
     *       1. Must follow {@link http://semver.org semantic versioning guidelines}.
     *       2. Must start with six digits.
     *       3. Must be all lowercase.
     */
    const WS_VERSION_REGEX_VALID = '/^'.
        '(?:[0-9]{6}(?:\.(?:[0-9](?:[0-9]|\.(?!\.))*[0-9]|[0-9]))?)'.
        '(?:\-(?:[a-z0-9](?:[a-z0-9]|[.\-](?![.\-]))*[a-z0-9]|[a-z0-9]))?'.
        '(?:\+(?:[a-z0-9](?:[a-z0-9]|[.\-](?![.\-]))*[a-z0-9]|[a-z0-9]))?'.
    '$/u';

    /**
     * Plugin dev version string validation pattern.
     *
     * @since 150424 Initial release.
     *
     * @var string Plugin dev version string validation pattern.
     *             This has additional limitations (but still compatible w/ PHP dev version strings).
     *
     * @internal Requirements are as follows:
     *
     *       1. Must follow {@link http://semver.org semantic versioning guidelines}.
     *       2. Must start with six digits.
     *       3. Must be all lowercase.
     *       4. Must have a development-state suffix; perhaps followed by an optional build suffix.
     */
    const WS_VERSION_REGEX_VALID_DEV = '/^'.
        '(?:[0-9]{6}(?:\.(?:[0-9](?:[0-9]|\.(?!\.))*[0-9]|[0-9]))?)'.
        '(?:\-(?:[a-z0-9](?:[a-z0-9]|[.\-](?![.\-]))*[a-z0-9]|[a-z0-9]))'.
        '(?:\+(?:[a-z0-9](?:[a-z0-9]|[.\-](?![.\-]))*[a-z0-9]|[a-z0-9]))?'.
    '$/u';

    /**
     * Plugin stable version string validation pattern.
     *
     * @since 150424 Initial release.
     *
     * @var string Plugin stable version string validation pattern.
     *             This has additional limitations (but still compatible w/ PHP stable version strings).
     *
     * @internal Requirements are as follows:
     *
     *       1. Must follow {@link http://semver.org semantic versioning guidelines}.
     *       2. Must start with six digits.
     *       3. Must be all lowercase.
     *       4. Must not contain a development-state suffix (i.e., it must be a stable version).
     *             However, it may contain an optional build suffix; and still be stable.
     */
    const WS_VERSION_REGEX_VALID_STABLE = '/^'.
        '(?:[0-9]{6}(?:\.(?:[0-9](?:[0-9]|\.(?!\.))*[0-9]|[0-9]))?)'.
        '(?:\+(?:[a-z0-9](?:[a-z0-9]|[.\-](?![.\-]))*[a-z0-9]|[a-z0-9]))?'.
    '$/u';
}
