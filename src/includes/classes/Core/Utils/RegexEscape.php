<?php
/**
 * Regex escape utilities.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Regex escape utilities.
 *
 * @since 150424 Initial release.
 */
class RegexEscape extends Classes\Core\Base\Core
{
    /**
     * Escape regex meta chars deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed  $value     Any input value.
     * @param string $delimiter Defaults to `/`.
     *
     * @return string|array|object Quoted deeply.
     */
    public function __invoke($value, string $delimiter = '/')
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($_value, $delimiter);
            } // unset($_key, $_value);
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        return preg_quote($string, $delimiter);

        // Multibyte safe. See: http://jas.xyz/1PvQJty
    }

    /**
     * Matches zero or more escapable chars,
     * and anything that's not vertical whitespace.
     *
     * @since 170211.63148 Markdown enhancements.
     *
     * @param string $escapable_chars Escapable chars.
     * @param bool   $ungreedy        Ungreedy matching?
     *
     * @return string Regex fragment suitable for `preg_*()`.
     */
    public function m0EscNoVws(string $escapable_chars, bool $ungreedy = false): string
    {
        $escapable_chars = $this->__invoke($escapable_chars);
        return '(?:[^\v'.$escapable_chars.'\\\\]|\\\\['.$escapable_chars.'])*'.($ungreedy ? '?' : '');
    }
}
