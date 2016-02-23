<?php
// @codingStandardsIgnoreFile

declare (strict_types = 1);
namespace WebSharks\Core\Functions;

error_reporting(-1);
ini_set('display_errors', 'yes');

if (PHP_SAPI !== 'cli') {
    exit('Requires CLI access.');
}

$_load = '<?php
// This file was auto-generated:
// '.date('F jS, Y, g:i a T').'

declare (strict_types = 1);
namespace WebSharks\Core\Functions;

';
foreach (dir_recursive_regex(__DIR__, '/\.php$/ui') as $_file) {
    if (mb_strpos(basename($_sub_path_name = $_file->getSubPathname()), '_') !== 0) {
        $_load .= "require_once __DIR__.'/".$_file->getSubPathname()."';\n";
    }
} // unset($_file); // Housekeeping.

file_put_contents(__DIR__.'/_load.php', $_load);
echo $_load; // Print for debugging purposes.

function dir_recursive_regex(string $dir, string $regex): \RegexIterator
{
    $DirIterator      = new \RecursiveDirectoryIterator($dir, \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::CURRENT_AS_SELF | \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::UNIX_PATHS);
    $IteratorIterator = new \RecursiveIteratorIterator($DirIterator, \RecursiveIteratorIterator::CHILD_FIRST);
    $RegexIterator    = new \RegexIterator($IteratorIterator, $regex, \RegexIterator::MATCH, \RegexIterator::USE_KEY);

    return $RegexIterator;
}
