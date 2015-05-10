<?php
namespace WebSharks\Core\Traits;

/**
 * Trim utilities.
 *
 * @since 150424 Initial release.
 */
trait TrimUtils
{
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
    protected function trim($value, $chars = '', $extra_chars = '', $side = '')
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->trim($_value, $chars, $extra_chars, $side);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        $value       = (string) $value;
        $chars       = (string) $chars;
        $extra_chars = (string) $extra_chars;
        $chars       = isset($chars[0]) ? $chars : " \r\n\t\0\x0B";
        $chars       = $chars.$extra_chars;
        $side        = strtolower((string) $side);

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
    protected function trimLeft($value, $chars = '', $extra_chars = '')
    {
        return $this->trim($value, $chars, $extra_chars, 'l');
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
    protected function trimRight($value, $chars = '', $extra_chars = '')
    {
        return $this->trim($value, $chars, $extra_chars, 'r');
    }
}
