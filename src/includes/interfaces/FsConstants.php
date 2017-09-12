<?php
/**
 * FS-related constants.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Interfaces;

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
     * @var string Regex fragment for use in `preg_match()`.
     */
    const FS_REGEX_FRAG_STREAM_WRAPPER = '[a-zA-Z][a-zA-Z0-9+.\-]*\:\/\/';

    /**
     * Regex matches a drive `C:\` prefix.
     *
     * @since 150424 Initial release.
     *
     * @var string Regex fragment for use in `preg_match()`.
     */
    const FS_REGEX_FRAG_DRIVE_PREFIX = '[a-zA-Z])\:[\/\\\\]';
}
