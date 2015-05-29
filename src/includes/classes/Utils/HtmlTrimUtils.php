<?php
namespace WebSharks\Core\Classes\Utils;

/**
 * HTML trim utilities.
 *
 * @since 150424 Initial release.
 */
class HtmlTrimUtils extends AbsBase
{
    abstract public function &staticKey($function, $args = array());
    abstract public function trim($value, $chars = '', $extra_chars = '', $side = '');

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
     * Trims HTML content deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed  $value       Any input value.
     * @param string $chars       Defaults to: " \r\n\t\0\x0B".
     *                            HTML whitespace is always trimmed, no matter.
     * @param string $extra_chars Additional specific chars to trim.
     * @param string $side        Optional; one of `l` or `r`.
     *
     * @return string|array|object Trimmed string, array, object.
     */
    public function htmlTrim($value, $chars = '', $extra_chars = '', $side = '')
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->htmlTrim($_value, $chars, $extra_chars, $side);
            }
            unset($_key, $_value); // Housekeeping.

            return $this->trim($value, $chars, $extra_chars, $side);
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

    /**
     * Left-trims an HTML content string.
     *
     * @since 150424 Initial release.
     *
     * @param mixed  $value       Any input value.
     * @param string $chars       Defaults to: " \r\n\t\0\x0B".
     *                            HTML whitespace is always trimmed, no matter.
     * @param string $extra_chars Additional specific chars to trim.
     *
     * @return string|array|object Trimmed string, array, object.
     */
    public function htmlTrimLeft($value, $chars = '', $extra_chars = '')
    {
        return $this->htmlTrim($value, $chars, $extra_chars, 'l');
    }

    /**
     * Right-trims an HTML content string.
     *
     * @since 150424 Initial release.
     *
     * @param mixed  $value       Any input value.
     * @param string $chars       Defaults to: " \r\n\t\0\x0B".
     *                            HTML whitespace is always trimmed, no matter.
     * @param string $extra_chars Additional specific chars to trim.
     *
     * @return string|array|object Trimmed string, array, object.
     */
    public function htmlTrimRight($value, $chars = '', $extra_chars = '')
    {
        return $this->htmlTrim($value, $chars, $extra_chars, 'r');
    }
}
