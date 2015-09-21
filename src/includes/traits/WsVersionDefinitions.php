<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits;

/**
 * Definition properties.
 *
 * @since 150424 Initial release.
 */
trait WsVersionDefinitions
{
    /**
     * Plugin version string validation pattern.
     *
     * @since 150424 Initial release.
     *
     * @type string Plugin version string validation pattern.
     *             This has additional limitations (but still compatible w/ PHP version strings).
     *
     * @note Requirements are as follows:
     *
     *       1. Must follow {@link http://semver.org semantic versioning guidelines}.
     *       2. Must start with six digits.
     *       3. Must be all lowercase.
     */
    protected $DEF_WS_VERSION_REGEX_VALID = '/^(?:[0-9]{6}(?:\.(?:[0-9](?:[0-9]|\.(?!\.))*[0-9]|[0-9]))?)(?:\-(?:[a-z0-9](?:[a-z0-9]|[.\-](?![.\-]))*[a-z0-9]|[a-z0-9]))?(?:\+(?:[a-z0-9](?:[a-z0-9]|[.\-](?![.\-]))*[a-z0-9]|[a-z0-9]))?$/';

    /**
     * Plugin dev version string validation pattern.
     *
     * @since 150424 Initial release.
     *
     * @type string Plugin dev version string validation pattern.
     *             This has additional limitations (but still compatible w/ PHP dev version strings).
     *
     * @note Requirements are as follows:
     *
     *       1. Must follow {@link http://semver.org semantic versioning guidelines}.
     *       2. Must start with six digits.
     *       3. Must be all lowercase.
     *       4. Must have a development-state suffix; perhaps followed by an optional build suffix.
     */
    protected $DEF_WS_VERSION_REGEX_VALID_DEV = '/^(?:[0-9]{6}(?:\.(?:[0-9](?:[0-9]|\.(?!\.))*[0-9]|[0-9]))?)(?:\-(?:[a-z0-9](?:[a-z0-9]|[.\-](?![.\-]))*[a-z0-9]|[a-z0-9]))(?:\+(?:[a-z0-9](?:[a-z0-9]|[.\-](?![.\-]))*[a-z0-9]|[a-z0-9]))?$/';

    /**
     * Plugin stable version string validation pattern.
     *
     * @since 150424 Initial release.
     *
     * @type string Plugin stable version string validation pattern.
     *             This has additional limitations (but still compatible w/ PHP stable version strings).
     *
     * @note Requirements are as follows:
     *
     *       1. Must follow {@link http://semver.org semantic versioning guidelines}.
     *       2. Must start with six digits.
     *       3. Must be all lowercase.
     *       4. Must not contain a development-state suffix (i.e., it must be a stable version).
     *             However, it may contain an optional build suffix; and still be stable.
     */
    protected $DEF_WS_VERSION_REGEX_VALID_STABLE = '/^(?:[0-9]{6}(?:\.(?:[0-9](?:[0-9]|\.(?!\.))*[0-9]|[0-9]))?)(?:\+(?:[a-z0-9](?:[a-z0-9]|[.\-](?![.\-]))*[a-z0-9]|[a-z0-9]))?$/';
}
