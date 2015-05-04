<?php
namespace WebSharks\Core\Traits;

/**
 * HTML conditional utilities.
 *
 * @since 150424 Initial release.
 */
trait HtmlIsUtils
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
    protected function htmlIs($string)
    {
        if (!$string || !is_string($string)) {
            return false; // Not possible.
        }
        return strpos($string, '<') !== false && preg_match('/\<[^<>]+\>/', $string);
    }
}
