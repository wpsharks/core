<?php
namespace WebSharks\Core\Classes\Utils;

/**
 * FS dir utilities.
 *
 * @since 150424 Initial release.
 */
class FsDirUtils extends AbsBase
{
    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        parent::__construct();
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
    public function fsDirRm($dir, $recursively = false)
    {
        $dir = (string) $dir;
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
                if (!$this->fsDirRm($_dir_file, $recursively)) {
                    return false;
                }
            } elseif (!unlink($_dir_file)) {
                return false;
            }
        }
        closedir($opendir); // Close resource now.
        unset($_dir_file); // Housekeeping.

        return rmdir($dir);
    }

    /**
     * Acquires a readable/writable tmp directory.
     *
     * @since 150424 Initial release.
     *
     * @throws \Exception If unable to find a temporary directory.
     *
     * @return string A readable/writable tmp directory.
     */
    public function fsDirTmp()
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
        if (stripos(PHP_OS, 'win') === 0) {
            $possible_dirs[] = 'C:/Temp';
        }
        if (stripos(PHP_OS, 'win') !== 0) {
            $possible_dirs[] = '/tmp';
        }
        if (defined('WP_CONTENT_DIR')) {
            $possible_dirs[] = (string) WP_CONTENT_DIR;
        }
        foreach ($possible_dirs as $_key => $_dir) {
            if (($_dir = trim((string) $_dir)) && @is_dir($_dir) && @is_writable($_dir)) {
                if (is_dir($_dir = $_dir.'/'.md5(__NAMESPACE__)) || mkdir($_dir, 0777, true)) {
                    return ($dir = $this->fsDirNSeps($_dir));
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
     * @return string Normalized directory/file path.
     */
    public function fsDirNSeps($value, $allow_trailing_slash = false)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->fsDirNSeps($_value);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        $string = (string) $value;
        if (!isset($string[0])) {
            return ''; // Empty.
        }
        if (strpos($string, '://' !== false)) {
            if (preg_match('/^(?P<stream_wrapper>[a-zA-Z0-9]+)\:\/\//', $string, $stream_wrapper)) {
                $string = preg_replace('/^(?P<stream_wrapper>[a-zA-Z0-9]+)\:\/\//', '', $string);
            }
        }
        if (strpos($string, ':' !== false)) {
            if (preg_match('/^(?P<drive_letter>[a-zA-Z])\:[\/\\\\]/', $string)) {
                $string = preg_replace_callback(
                    '/^(?P<drive_letter>[a-zA-Z])\:[\/\\\\]/',
                    function ($m) {
                        return strtoupper($m[0]);
                    },
                    $string
                );
            }
        }
        $string = str_replace(array(DIRECTORY_SEPARATOR, '\\', '/'), '/', $string);
        $string = preg_replace('/\/+/', '/', $string);
        $string = $allow_trailing_slash ? $string : rtrim($string, '/');

        if (!empty($stream_wrapper[0])) {
            $string = strtolower($stream_wrapper[0]).$string;
        }
        return $string;
    }

    /**
     * Creates a recursive directory/regex iterator.
     *
     * @since 150424 Initial release.
     *
     * @param string $dir   Directory to scan.
     * @param string $regex Regular expression.
     *
     * @throws \Exception If either of the input parameters are empty.
     *
     * @return \RegexIterator|\RecursiveDirectoryIterator[]
     */
    public function fsDirRegexIteration($dir, $regex)
    {
        if (!($dir = (string) $dir) || !($regex = (string) $regex)) {
            throw new \Exception('Missing required `$dir` and/or `$regex` parameters.');
        }
        $dir_iterator      = new \RecursiveDirectoryIterator($dir, \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::CURRENT_AS_SELF | \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::UNIX_PATHS);
        $iterator_iterator = new \RecursiveIteratorIterator($dir_iterator, \RecursiveIteratorIterator::CHILD_FIRST);
        $regex_iterator    = new \RegexIterator($iterator_iterator, $regex, \RegexIterator::MATCH, \RegexIterator::USE_KEY);

        return $regex_iterator;
    }
}
