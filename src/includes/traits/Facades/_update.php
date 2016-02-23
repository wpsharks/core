<?php
// @codingStandardsIgnoreFile

declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

error_reporting(-1);
ini_set('display_errors', 'yes');

if (PHP_SAPI !== 'cli') {
    exit('Requires CLI access.');
}
$counter = 0;
foreach (dir_recursive_regex(dirname(__FILE__, 4), '/\.php$/ui') as $_file) {
    if (mb_strpos(basename($_file->getSubPathname()), '_') !== 0) {
        $_contents = file_get_contents($_file->getPathname());
        $_contents = preg_replace_callback('/\bc\\\\([a-z][a-z0-9_]*)\(/u', function ($m) use (&$counter) {
            ++$counter;
            $name = preg_replace_callback('/_(.)/u', function ($m) {
                return mb_strtoupper($m[1]);
            }, $m[1]);
            $name = preg_replace('/Ltrim$/', 'LTrim', $name);
            $name = preg_replace('/Rtrim$/', 'RTrim', $name);
            $name = preg_replace('/Lcfirst$/', 'LcFirst', $name);
            $name = preg_replace('/Strcasecmp$/', 'StrCaseCmp', $name);
            $name = preg_replace('/Strrev$/', 'StrRev', $name);
            $name = preg_replace('/Ucfirst$/', 'UcFirst', $name);
            $name = preg_replace('/Ucwords$/', 'UcWords', $name);
            $name = preg_replace('/oembed$/', 'oEmbed', $name);
            $name = preg_replace('/oembed$/', 'oEmbed', $name);
            $name = preg_replace('/IreplaceOnce$/', 'IReplaceOnce', $name);
            echo $m[0].' = $this->a::'.$name.'('."\n";
            return '$this->a::'.$name.'(';
        }, $_contents);
        //file_put_contents($_file->getPathname(), $_contents);
    }
} // unset($_file); // Housekeeping.
echo $counter."\n";

function dir_recursive_regex(string $dir, string $regex): \RegexIterator
{
    $DirIterator      = new \RecursiveDirectoryIterator($dir, \FilesystemIterator::KEY_AS_PATHNAME | \FilesystemIterator::CURRENT_AS_SELF | \FilesystemIterator::SKIP_DOTS | \FilesystemIterator::UNIX_PATHS);
    $IteratorIterator = new \RecursiveIteratorIterator($DirIterator, \RecursiveIteratorIterator::CHILD_FIRST);
    $RegexIterator    = new \RegexIterator($IteratorIterator, $regex, \RegexIterator::MATCH, \RegexIterator::USE_KEY);

    return $RegexIterator;
}
