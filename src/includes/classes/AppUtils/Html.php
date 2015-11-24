<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * HTML utilities.
 *
 * @since 150424 Initial release.
 */
class Html extends Classes\AbsBase
{
    /**
     * Is a string in HTML format?
     *
     * @since 150424 Initial release.
     *
     * @param string $string Any input string to test here.
     *
     * @return bool `TRUE` if string is HTML.
     */
    public function is(string $string): bool
    {
        if (!$string) {
            return false; // Not possible.
        }
        return mb_strpos($string, '<') !== false && preg_match('/\<[^<>]+\>/u', $string);
    }
}
