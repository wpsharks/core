<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function get_defined_vars as vars;

/**
 * Trim (multibyte-safe).
 *
 * @since 150424 Initial release.
 */
class Trim extends Classes\Core\Base\Core
{
    /**
     * Trim (multibyte-safe).
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
        $string = (string) $value;

        if (!isset($string[0])) {
            return $string; // Nothing to do.
        }
        $chars = isset($chars[0]) ? $chars : " \r\n\t\0\x0B";
        $chars = $this->c::escRegex($chars.$extra_chars);

        switch ($side) {
            case 'l': // Left trim.
                return preg_replace('/^['.$chars.']+/u', '', $string);

            case 'r': // Right trim.
                return preg_replace('/['.$chars.']+$/u', '', $string);

            default: // Both sides.
                return preg_replace('/^['.$chars.']+|['.$chars.']+$/u', '', $string);
        }
    }

    /**
     * Trim (multibyte-safe) on left.
     *
     * @since 150424 Initial release.
     *
     * @param mixed  $value       Any input value.
     * @param string $chars       Defaults to: " \r\n\t\0\x0B".
     * @param string $extra_chars Additional chars to trim.
     *
     * @return string|array|object Trimmed value.
     */
    public function l($value, string $chars = '', string $extra_chars = '')
    {
        return $this->__invoke($value, $chars, $extra_chars, 'l');
    }

    /**
     * Trim (multibyte-safe) on right.
     *
     * @since 150424 Initial release.
     *
     * @param mixed  $value       Any input value.
     * @param string $chars       Defaults to: " \r\n\t\0\x0B".
     * @param string $extra_chars Additional chars to trim.
     *
     * @return string|array|object Trimmed value.
     */
    public function r($value, string $chars = '', string $extra_chars = '')
    {
        return $this->__invoke($value, $chars, $extra_chars, 'r');
    }
}
