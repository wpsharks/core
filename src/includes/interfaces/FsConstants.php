<?php
declare (strict_types = 1);
namespace WebSharks\Core\Interfaces;

/**
 * FS-related constants.
 *
 * @since 150424 Initial release.
 */
interface FsConstants
{
    /**
     * Regex matches a `stream://`.
     *
     * @since 150424 Initial release.
     *
     * @type string Regex fragment for use in `preg_match()`.
     */
    const FS_REGEX_FRAG_STREAM_WRAPPER = '[a-zA-Z][a-zA-Z0-9+.\-]*\:\/\/';

    /**
     * Regex matches a drive `C:\` prefix.
     *
     * @since 150424 Initial release.
     *
     * @type string Regex fragment for use in `preg_match()`.
     */
    const FS_REGEX_FRAG_DRIVE_PREFIX = '[a-zA-Z])\:[\/\\\\]';
}
