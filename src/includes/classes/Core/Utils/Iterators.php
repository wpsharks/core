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
 * Iterator utilities.
 *
 * @since 150424 Initial release.
 */
class Iterators extends Classes\Core\Base\Core
{
    /**
     * Recursive array iterator.
     *
     * @since 150424 Array utils.
     *
     * @param array $array Input array.
     *
     * @return \RecursiveIteratorIterator Recursive iterator.
     */
    public function arrayRecursive(array $array): \RecursiveIteratorIterator
    {
        return new \RecursiveIteratorIterator(new \RecursiveArrayIterator($array));
    }

    /**
     * Recursive dir/regex iterator.
     *
     * @since 150424 Initial release.
     *
     * @param string $dir   Directory to scan.
     * @param string $regex Regular expression.
     *
     * @throws Exception If either of the input parameters are empty.
     *
     * @return \RegexIterator Recursive dir/regex iterator.
     */
    public function dirRecursiveRegex(string $dir, string $regex = '/.+/u'): \RegexIterator
    {
        if (!$dir || !$regex) {
            throw new Exception('Missing required `$dir` and/or `$regex` parameters.');
        }
        $DirIterator      = new \RecursiveDirectoryIterator($dir, \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::CURRENT_AS_SELF | \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::UNIX_PATHS);
        $IteratorIterator = new \RecursiveIteratorIterator($DirIterator, \RecursiveIteratorIterator::CHILD_FIRST);
        $RegexIterator    = new \RegexIterator($IteratorIterator, $regex, \RegexIterator::MATCH, \RegexIterator::USE_KEY);

        return $RegexIterator;
    }
}
