<?php
namespace WebSharks\Core\Traits;

/**
 * Trim Utilities.
 *
 * @since 150424 Initial release.
 */
trait TrimUtils
{
    use Definitions;

    /**
     * Trims a string value.
     *
     * @param string $string      A string value.
     * @param string $chars       Specific chars to trim. Defaults to PHP's trim: " \r\n\t\0\x0B".
     *                            Use an empty string to bypass this argument and specify additional chars only.
     * @param string $extra_chars Additional chars to trim.
     *
     * @return string Trimmed string.
     */
    protected function trim($string, $chars = '', $extra_chars = '')
    {
        return $this->trimDeep((string) $string, $chars, $extra_chars);
    }

    /**
     * Trims strings deeply.
     *
     * @param mixed  $value       Any value can be converted into a trimmed string.
     *                            Actually, objects can't, but this recurses into objects.
     * @param string $chars       Specific chars to trim. Defaults to PHP's trim: " \r\n\t\0\x0B".
     *                            Use an empty string to bypass this argument and specify additional chars only.
     * @param string $extra_chars Additional chars to trim.
     *
     * @return string|array|object Trimmed string, array, object.
     */
    protected function trimDeep($value, $chars = '', $extra_chars = '')
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->trimDeep($_value, $chars, $extra_chars);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        $value       = (string) $value;
        $chars       = (string) $chars;
        $extra_chars = (string) $extra_chars;
        $chars       = isset($chars[0]) ? $chars : " \r\n\t\0\x0B";
        $chars       = $chars.$extra_chars;

        return trim($value, $chars);
    }

    /**
     * Trims an HTML content string.
     *
     * @param string $string      A string value.
     * @param string $chars       Other specific chars to trim. Defaults to PHP's trim: " \r\n\t\0\x0B".
     *                            Use an empty string to bypass this argument and specify additional chars only.
     *                            Note that HTML whitespace is always trimmed, no matter what you set this to.
     * @param string $extra_chars Additional specific chars to trim.
     *
     * @return string Trimmed string; HTML whitespace is always trimmed.
     */
    protected function trimContent($string, $chars = '', $extra_chars = '')
    {
        return $this->trimContentDeep((string) $string, $chars, $extra_chars);
    }

    /**
     * Trims an HTML content string deeply.
     *
     * @param mixed  $value       Any value can be converted into a trimmed string.
     *                            Actually, objects can't, but this recurses into objects.
     * @param string $chars       Other specific chars to trim. Defaults to PHP's trim: " \r\n\t\0\x0B".
     *                            Use an empty string to bypass this argument and specify additional chars only.
     *                            Note that HTML whitespace is always trimmed, no matter what you set this to.
     * @param string $extra_chars Additional specific chars to trim.
     *
     * @return string|array|object Trimmed string, array, object; HTML whitespace is always trimmed.
     */
    protected function trimContentDeep($value, $chars = '', $extra_chars = '')
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->trimContentDeep($_value, $chars, $extra_chars);
            }
            unset($_key, $_value); // Housekeeping.

            return $this->trimDeep($value, $chars, $extra_chars);
        }
        if (!isset($this->static[__FUNCTION__.'__whitespace'])) {
            $this->static[__FUNCTION__.'_whitespace'] = implode('|', array_keys($this->def_html_whitespace));
        }
        $whitespace = &$this->static[__FUNCTION__.'__whitespace']; // Shorter reference.

        $value = preg_replace('/^(?:'.$whitespace.')+|(?:'.$whitespace.')+$/', '', (string) $value);

        return $this->trim($value, $chars, $extra_chars);
    }

    /**
     * Trims double quotes deeply.
     *
     * @param mixed $value Any value can be converted into a trimmed string.
     *                     Actually, objects can't, but this recurses into objects.
     *
     * @return string|array|object Trimmed string, array, object.
     */
    protected function trimDqDeep($value)
    {
        return $this->trimDeep($value, '', '"');
    }

    /**
     * Trims single quotes deeply.
     *
     * @param mixed $value Any value can be converted into a trimmed string.
     *                     Actually, objects can't, but this recurses into objects.
     *
     * @return string|array|object Trimmed string, array, object.
     */
    protected function trimSqDeep($value)
    {
        return $this->trimDeep($value, '', "'");
    }

    /**
     * Trims double/single quotes deeply.
     *
     * @param mixed $value Any value can be converted into a trimmed string.
     *                     Actually, objects can't, but this recurses into objects.
     *
     * @return string|array|object Trimmed string, array, object.
     */
    protected function trimDsqDeep($value)
    {
        return $this->trimDeep($value, '', '"\'');
    }

    /**
     * Trims all single/double quotes, including entity variations deeply.
     *
     * @param mixed $value    Any value can be converted into a trimmed string.
     *                        Actually, objects can't, but this recurses into objects.
     * @param bool  $trim_dsq Defaults to TRUE.
     *                        If `FALSE`, normal double/single quotes will NOT be trimmed, only entities.
     *
     * @return string|array|object Trimmed string, array, object.
     */
    protected function trimQtsDeep($value, $trim_dsq = true)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->trimQtsDeep($_value, $trim_dsq, true);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        $qts = implode('|', array_keys($this->def_quote_entities));
        $qts = $trim_dsq ? $qts.'|"|\'' : $qts;

        return preg_replace('/^(?:'.$qts.')+|(?:'.$qts.')+$/', '', (string) $value);
    }
}
