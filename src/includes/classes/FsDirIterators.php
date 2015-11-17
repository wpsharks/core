<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * FS dir iterator utilities.
 *
 * @since 150424 Initial release.
 */
class FsDirIterators extends AbsBase
{
    /**
     * Creates a recursive directory/regex iterator.
     *
     * @since 150424 Initial release.
     *
     * @param string $dir   Directory to scan.
     * @param string $regex Regular expression.
     *
     * @throws Exception If either of the input parameters are empty.
     *
     * @return \RegexIterator|\RecursiveDirectoryIterator[]
     */
    public function recursiveRegex(string $dir, string $regex): \RegexIterator
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
