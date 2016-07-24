<?php
/**
 * FS-related constants.
 *
 * @author @jaswsinc
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
