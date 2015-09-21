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
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        parent::__construct();
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
    public function recursiveRegex($dir, $regex)
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
