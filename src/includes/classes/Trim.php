<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * Trim utilities.
 *
 * @since 150424 Initial release.
 */
class Trim extends AbsBase
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
     * Trims a value deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed  $value       Any input value.
     * @param string $chars       Defaults to: " \r\n\t\0\x0B".
     * @param string $extra_chars Additional chars to trim.
     * @param string $side        Optional; one of `l` or `r`.
     *
     * @return string|array|object Trimmed value.
     */
    public function __invoke($value, string $chars = '', string $extra_chars = '', string $side = '')
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($_value, $chars, $extra_chars, $side);
            } // unset($_key, $_value); // Housekeeping.

            return $value;
        }
        $value = (string) $value;
        $chars = isset($chars[0]) ? $chars : " \r\n\t\0\x0B";
        $chars = $chars.$extra_chars;
        $side  = strtolower($side);

        switch ($side) {
            case 'l': // Left trim.
                return ltrim($value, $chars);

            case 'r': // Right trim.
                return rtrim($value, $chars);

            default: // Both sides.
                return trim($value, $chars);
        }
    }

    /**
     * Trims a value deeply (on left).
     *
     * @since 150424 Initial release.
     *
     * @param mixed  $value       Any input value.
     * @param string $chars       Defaults to: " \r\n\t\0\x0B".
     * @param string $extra_chars Additional chars to trim.
     *
     * @return string|array|object Trimmed value.
     */
    public function left($value, string $chars = '', string $extra_chars = '')
    {
        return $this->__invoke($value, $chars, $extra_chars, 'l');
    }

    /**
     * Trims a value deeply (on right).
     *
     * @since 150424 Initial release.
     *
     * @param mixed  $value       Any input value.
     * @param string $chars       Defaults to: " \r\n\t\0\x0B".
     * @param string $extra_chars Additional chars to trim.
     *
     * @return string|array|object Trimmed value.
     */
    public function right($value, string $chars = '', string $extra_chars = '')
    {
        return $this->__invoke($value, $chars, $extra_chars, 'r');
    }
}
