<?php
/**
 * Dir tmp utilities.
 *
 * @author @jaswsinc
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
                $_dir .= '/'.$this->App->namespace_sha1.'/.tmp'; // App-specific.
                // Also using a `.tmp` directory to help improve security and designate tmp.
                if (is_dir($_dir) || (!file_exists($_dir) && mkdir($_dir, $fs_transient_perms, true))) {
                    return $dir = $_dir;
                }
            }
        } // unset($_key, $_dir); // Housekeeping.

        return $dir = '';
    }
}
