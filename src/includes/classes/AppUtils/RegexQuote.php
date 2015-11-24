<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Regex quote utilities.
 *
 * @since 150424 Initial release.
 */
class RegexQuote extends Classes\AbsBase
{
    /**
     * Quote regex meta chars deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed  $value     Any input value.
     * @param string $delimiter Delimiter to use. Defaults to `/`.
     *
     * @return string|array|object Quoted deeply.
     */
    public function __invoke($value, string $delimiter = '/')
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($_value, $delimiter);
            } // unset($_key, $_value); // Housekeeping.

            return $value;
        }
        $string = (string) $value;

        if (!isset($string[0])) {
            return $string;
        }
        return preg_quote($string, $delimiter);

        // Multibyte safe. See: http://jas.xyz/1PvQJty
    }
}
