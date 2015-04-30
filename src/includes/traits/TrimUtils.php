<?php
namespace WebSharks\Core\Traits;

/**
 * Trim Utilities.
 *
 * @since 150424 Initial release.
 */
trait TrimUtils
{
    abstract protected function &staticKey($function, $args = array());

    /**
     * Trims a string value.
     *
     * @param string $string      A string value.
     * @param string $chars       Defaults to: " \r\n\t\0\x0B".
     * @param string $extra_chars Additional chars to trim.
     * @param string $side        Optional; one of `l` or `r`.
     *
     * @return string Trimmed string.
     */
    protected function trim($string, $chars = '', $extra_chars = '', $side = '')
    {
        return $this->trimDeep((string) $string, $chars, $extra_chars, $side);
    }

    /**
     * Left-trims a string value.
     *
     * @param string $string      A string value.
     * @param string $chars       Defaults to: " \r\n\t\0\x0B".
     * @param string $extra_chars Additional chars to trim.
     *
     * @return string Trimmed string.
     */
    protected function lTrim($string, $chars = '', $extra_chars = '')
    {
        return $this->trimDeep((string) $string, $chars, $extra_chars, 'l');
    }

    /**
     * Right-trims a string value.
     *
     * @param string $string      A string value.
     * @param string $chars       Defaults to: " \r\n\t\0\x0B".
     * @param string $extra_chars Additional chars to trim.
     *
     * @return string Trimmed string.
     */
    protected function rTrim($string, $chars = '', $extra_chars = '')
    {
        return $this->trimDeep((string) $string, $chars, $extra_chars, 'r');
    }

    /**
     * Trims a value deeply.
     *
     * @param mixed  $value       Any value can be converted into a trimmed string.
     *                            Actually, objects can't, but this recurses into objects.
     * @param string $chars       Defaults to: " \r\n\t\0\x0B".
     * @param string $extra_chars Additional chars to trim.
     * @param string $side        Optional; one of `l` or `r`.
     *
     * @return string|array|object Trimmed string, array, object.
     */
    protected function trimDeep($value, $chars = '', $extra_chars = '', $side = '')
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->trimDeep($_value, $chars, $extra_chars, $side);
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
     * Trims an HTML content string.
     *
     * @param string $string      A string value.
     * @param string $chars       Defaults to: " \r\n\t\0\x0B".
     *                            HTML whitespace is always trimmed, no matter.
     * @param string $extra_chars Additional specific chars to trim.
     * @param string $side        Optional; one of `l` or `r`.
     *
     * @return string Trimmed string; HTML whitespace is always trimmed.
     */
    protected function trimContent($string, $chars = '', $extra_chars = '', $side = '')
    {
        return $this->trimContentDeep((string) $string, $chars, $extra_chars, $side);
    }

    /**
     * Left-trims an HTML content string.
     *
     * @param string $string      A string value.
     * @param string $chars       Defaults to: " \r\n\t\0\x0B".
     *                            HTML whitespace is always trimmed, no matter.
     * @param string $extra_chars Additional specific chars to trim.
     *
     * @return string Trimmed string; HTML whitespace is always trimmed.
     */
    protected function lTrimContent($string, $chars = '', $extra_chars = '')
    {
        return $this->trimContentDeep((string) $string, $chars, $extra_chars, 'l');
    }

    /**
     * Right-trims an HTML content string.
     *
     * @param string $string      A string value.
     * @param string $chars       Defaults to: " \r\n\t\0\x0B".
     *                            HTML whitespace is always trimmed, no matter.
     * @param string $extra_chars Additional specific chars to trim.
     *
     * @return string Trimmed string; HTML whitespace is always trimmed.
     */
    protected function rTrimContent($string, $chars = '', $extra_chars = '')
    {
        return $this->trimContentDeep((string) $string, $chars, $extra_chars, 'r');
    }

    /**
     * Trims HTML content deeply.
     *
     * @param mixed  $value       Any value can be converted into a trimmed string.
     *                            Actually, objects can't, but this recurses into objects.
     * @param string $chars       Defaults to: " \r\n\t\0\x0B".
     *                            HTML whitespace is always trimmed, no matter.
     * @param string $extra_chars Additional specific chars to trim.
     * @param string $side        Optional; one of `l` or `r`.
     *
     * @return string|array|object Trimmed string, array, object; HTML whitespace is always trimmed.
     */
    protected function trimContentDeep($value, $chars = '', $extra_chars = '', $side = '')
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->trimContentDeep($_value, $chars, $extra_chars, $side);
            }
            unset($_key, $_value); // Housekeeping.

            return $this->trimDeep($value, $chars, $extra_chars, $side);
        }
        $value = (string) $value;
        $side  = strtolower((string) $side);

        if (is_null($whitespace = &$this->staticKey(__FUNCTION__.'_whitespace'))) {
            $whitespace = implode('|', array_keys($this->def_html_whitespace));
        }
        switch ($side) {
            case 'l': // Left trim.
                $value = preg_replace('/^(?:'.$whitespace.')+/', '', $value);
                break; // Break switch handler.

            case 'r': // Right trim.
                $value = preg_replace('/(?:'.$whitespace.')+$/', '', $value);
                break; // Break switch handler.

            default: // Both sides.
                $value = preg_replace('/^(?:'.$whitespace.')+|(?:'.$whitespace.')+$/', '', $value);
                break; // Break switch handler.
        }
        return $this->trim($value, $chars, $extra_chars, $side);
    }
}
