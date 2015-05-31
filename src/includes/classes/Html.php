<?php
namespace WebSharks\Core\Classes;

/**
 * HTML utilities.
 *
 * @since 150424 Initial release.
 */
class Html extends AbsBase
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
     * Is a string in HTML format?
     *
     * @since 150424 Initial release.
     *
     * @param string $string Any input string to test here.
     *
     * @return bool `TRUE` if string is HTML.
     */
    public function is($string)
    {
        if (!$string || !is_string($string)) {
            return false; // Not possible.
        }
        return strpos($string, '<') !== false && preg_match('/\<[^<>]+\>/', $string);
    }
}
