<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * HTML utilities.
 *
 * @since 150424 Initial release.
 */
class Html extends Classes\AppBase
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
        return $string && mb_strpos($string, '<') !== false
                && preg_match('/\<[^<>]+\>/u', $string);
    }

    /**
     * Checked?
     *
     * @since 15xxxx Initial release.
     *
     * @param mixed $a Input variable a.
     * @param mixed $b Input variable b.
     *
     * @return string ` checked` if true.
     */
    public function checked($a, $b)
    {
        return (string) $a === (string) $b ? ' checked' : '';
    }

    /**
     * Selected?
     *
     * @since 15xxxx Initial release.
     *
     * @param mixed $a Input variable a.
     * @param mixed $b Input variable b.
     *
     * @return string ` selected` if true.
     */
    public function selected($a, $b)
    {
        return (string) $a === (string) $b ? ' selected' : '';
    }
}
