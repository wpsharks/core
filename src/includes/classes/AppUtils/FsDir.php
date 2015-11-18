<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;

/**
 * FS dir utilities.
 *
 * @since 150424 Initial release.
 */
class FsDir extends Classes\AbsBase
{
    /**
     * Acquires a readable/writable tmp directory.
     *
     * @since 150424 Initial release.
     *
     * @throws Exception If unable to find a temporary directory.
     *
     * @return string A readable/writable tmp directory.
     */
    public function tmp(): string
    {
        if (!is_null($dir = &$this->staticKey(__FUNCTION__))) {
            return $dir; // Already cached this.
        }
        $possible_dirs = []; // Initialize.

        if (defined('WP_TEMP_DIR')) {
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
        if (mb_stripos(PHP_OS, 'win') === 0) {
            $possible_dirs[] = 'C:/Temp';
        }
        if (mb_stripos(PHP_OS, 'win') !== 0) {
            $possible_dirs[] = '/tmp';
        }
        if (defined('WP_CONTENT_DIR')) {
            $possible_dirs[] = (string) WP_CONTENT_DIR;
        }
        foreach ($possible_dirs as $_key => $_dir) {
            if (($_dir = $this->Utils->Trim((string) $_dir)) && @is_dir($_dir) && @is_writable($_dir)) {
                if (is_dir($_dir = $_dir.'/'.md5(__NAMESPACE__)) || mkdir($_dir, 0777, true)) {
                    return ($dir = $this->normalize($_dir));
                }
            }
        }
        unset($_key, $_dir); // Housekeeping.

        return ($dir = '');
    }

    /**
     * Normalizes directory separators deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value                Any input value.
     * @param bool  $allow_trailing_slash Optional; defaults to a FALSE value.
     *
     * @return string|array|object Normalized directory/file path.
     */
    public function normalize($value, bool $allow_trailing_slash = false)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->normalize($_value);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        $string = (string) $value;
        if (!isset($string[0])) {
            return ''; // Empty.
        }
        if (mb_strpos($string, '://') !== false) {
            if (preg_match('/^(?P<stream_wrapper>[a-zA-Z0-9]+)\:\/\//u', $string, $stream_wrapper)) {
                $string = preg_replace('/^(?P<stream_wrapper>[a-zA-Z0-9]+)\:\/\//u', '', $string);
            }
        }
        if (mb_strpos($string, ':') !== false) {
            if (preg_match('/^(?P<drive_letter>[a-zA-Z])\:[\/\\\\]/u', $string)) {
                $string = preg_replace_callback(
                    '/^(?P<drive_letter>[a-zA-Z])\:[\/\\\\]/u',
                    function ($m) {
                        return mb_strtoupper($m[0]);
                    },
                    $string
                );
            }
        }
        $string = str_replace(array(DIRECTORY_SEPARATOR, '\\', '/'), '/', $string);
        $string = preg_replace('/\/+/u', '/', $string);
        $string = $allow_trailing_slash ? $string : $this->Utils->Trim->r($string, '/');

        if (!empty($stream_wrapper[0])) {
            $string = mb_strtolower($stream_wrapper[0]).$string;
        }
        return $string;
    }

    /**
     * Removes a directory.
     *
     * @since 150424 Initial release.
     *
     * @param string $dir         Directory to remove; possibly recursively.
     * @param bool   $recursively Optional; defaults to a `FALSE` value.
     *
     * @return bool TRUE if the directory was removed successfully.
     */
    public function remove(string $dir, bool $recursively = false): bool
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
            return false;
        }
        while (($_dir_file = readdir($opendir)) !== false) {
            if (in_array($_dir_file, array('.', '..'), true)) {
                continue; // Skip dots.
            }
            if (is_dir($_dir_file = $dir.'/'.$_dir_file)) {
                if (!$this->remove($_dir_file, $recursively)) {
                    return false;
                }
            } elseif (!unlink($_dir_file)) {
                return false;
            }
        }
        closedir($opendir); // Close resource now.
        unset($_dir_file); // Force deference.

        return rmdir($dir);
    }
}
