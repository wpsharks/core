<?php
namespace WebSharks\Core\Traits;

/**
 * Regex quote utilities.
 *
 * @since 150424 Initial release.
 */
trait RegexQuoteUtils
{
    /**
     * Quote regex meta chars deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed       $value     Any input value.
     * @param null|string $delimiter Delimiter to use. Defaults to `/`.
     *
     * @return string|array|object Quoted deeply.
     */
    protected function regexQuote($value, $delimiter = '/')
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->regexQuote($_value, $delimiter);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        $string = (string) $value;

        return preg_quote($string, $delimiter);
    }
}