<?php
namespace WebSharks\Core\Classes\Utils;

/**
 * EOL utilities.
 *
 * @since 150424 Initial release.
 */
class EolUtils extends AbsBase
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
     * Normalizes end of line chars deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value Any input value.
     *
     * @return string|array|object With normalized end of line chars deeply.
     */
    public function eolsN($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->eolsN($_value);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        $string = (string) $value;
        $string = str_replace(array("\r\n", "\r"), "\n", $string);
        $string = preg_replace('/'."\n".'{3,}/', "\n\n", $string);

        return $string;
    }
}
