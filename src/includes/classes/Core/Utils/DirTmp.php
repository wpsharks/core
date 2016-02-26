<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * DirTmp utilities.
 *
 * @since 150424 Initial release.
 */
class DirTmp extends Classes\Core
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
        if (!is_null($dir = &$this->cacheKey(__FUNCTION__))) {
            return $dir; // Already cached this.
        }
        $possible_dirs = []; // Initialize.
        $is_wordpress  = $this->a::isWordpress();

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
        if ($this->a::isWindows()) {
            $possible_dirs[] = 'C:/Temp';
        } else {
            $possible_dirs[] = '/tmp';
        }
        if ($is_wordpress && defined('WP_CONTENT_DIR')) {
            $possible_dirs[] = (string) WP_CONTENT_DIR;
        }
        foreach ($possible_dirs as $_key => $_dir) {
            if ($_dir && @is_dir($_dir) && @is_writable($_dir)) {
                $_dir .= '/'.$this->App->namespace_sha1.'/'.$this->App->dir_sha1;
                if (is_dir($_dir) || mkdir($_dir, $this->App->Config->©fs_permissions['©transient_dirs'], true)) {
                    return $dir = $_dir;
                }
            }
        } // unset($_key, $_dir); // Housekeeping.

        return $dir = '';
    }
}
