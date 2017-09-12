<?php
/**
 * Dir tmp utilities.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

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
 * Dir tmp utilities.
 *
 * @since 150424 Initial release.
 */
class DirTmp extends Classes\Core\Base\Core
{
    /**
     * Readable/writable tmp dir.
     *
     * @since 150424 Initial release.
     *
     * @return string Readable/writable tmp dir.
     */
    public function __invoke(): string
    {
        if (($dir = &$this->cacheKey(__FUNCTION__)) !== null) {
            return $dir; // Already cached this.
        }
        $possible_dirs      = []; // Initialize.
        $is_wordpress       = $this->c::isWordPress();
        $fs_transient_perms = $this->App->Config->©fs_permissions['©transient_dirs'];

        if ($is_wordpress && defined('WP_TEMP_DIR')) {
            $possible_dirs[] = (string) WP_TEMP_DIR;
        }
        $possible_dirs[] = (string) sys_get_temp_dir();
        $possible_dirs[] = (string) ini_get('upload_tmp_dir');

        if (!empty($_SERVER['TEMP'])) {
            $possible_dirs[] = (string) $_SERVER['TEMP'];
        }
        if (!empty($_SERVER['TMPDIR'])) {
            $possible_dirs[] = (string) $_SERVER['TMPDIR'];
        }
        if (!empty($_SERVER['TMP'])) {
            $possible_dirs[] = (string) $_SERVER['TMP'];
        }
        if ($this->c::isWindows()) {
            $possible_dirs[] = 'C:/Temp';
        } else {
            $possible_dirs[] = '/tmp';
        }
        if ($is_wordpress && defined('WP_CONTENT_DIR')) {
            $possible_dirs[] = (string) WP_CONTENT_DIR;
        }
        foreach ($possible_dirs as $_key => $_dir) {
            if ($_dir && @is_dir($_dir) && @is_writable($_dir)) {
                $_dir = $this->c::mbRTrim($_dir, '/');
                $_dir .= '/'.$this->App->Config->©brand['©slug'].'/.tmp';
                // Also using a `.tmp` (dot) directory to help improve security.
                if (is_dir($_dir) || (!file_exists($_dir) && mkdir($_dir, $fs_transient_perms, true))) {
                    return $dir = $_dir;
                }
            }
        } // unset($_key, $_dir); // Housekeeping.

        $dir = ''; // Empty string and exception on failure.
        throw $this->c::issue(vars(), 'Unable to locate a tmp directory.');
    }
}
