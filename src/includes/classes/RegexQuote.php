<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * Regex quote utilities.
 *
 * @since 150424 Initial release.
 */
class RegexQuote extends AbsBase
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

        return preg_quote($string, $delimiter);
    }
}
