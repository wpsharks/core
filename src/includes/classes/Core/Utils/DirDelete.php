<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function get_defined_vars as vars;

/**
 * DirDelete utilities.
 *
 * @since 150424 Initial release.
 */
class DirDelete extends Classes\Core\Base\Core
{
    /**
     * Deletes a directory.
     *
     * @since 150424 Initial release.
     *
     * @param string $dir         Directory to delete.
     * @param bool   $recursively Defaults to a `false` value.
     *
     * @return bool True if the directory was deleted successfully.
     */
    public function __invoke(string $dir, bool $recursively = false): bool
    {
        if (!$dir || !is_dir($dir)) {
            return true;
        }
        if (!is_writable($dir)) {
            return false;
        }
        if (!$recursively) {
            return rmdir($dir);
        }
        if (!($opendir = opendir($dir))) {
            return false; // Not possible.
        }
        while (($_dir_file = readdir($opendir)) !== false) {
            if (in_array($_dir_file, ['.', '..'], true)) {
                continue; // Skip dots.
            }
            if (is_dir($_dir_file = $dir.'/'.$_dir_file)) {
                if (!$this->__invoke($_dir_file, $recursively)) {
                    return false;
                }
            } elseif (!unlink($_dir_file)) {
                return false;
            }
        } // unset($_dir_file); // Housekeeping.
        closedir($opendir); // Close resource now.

        return rmdir($dir);
    }
}
