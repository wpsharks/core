<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * HTML trim utilities.
 *
 * @since 150424 Initial release.
 */
class HtmlTrim extends Classes\Core implements Interfaces\HtmlConstants
{
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
    public function __invoke($value, string $chars = '', string $extra_chars = '', string $side = '')
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($_value, $chars, $extra_chars, $side);
            } // unset($_key, $_value); // Housekeeping.
            return $value;
        }
        $string = (string) $value; // Force string.

        if (!isset($string[0])) {
            return $string; // Nothing to do.
        }
        if (is_null($whitespace = &$this->cacheKey(__FUNCTION__.'_whitespace'))) {
            $whitespace = implode('|', array_keys($this::HTML_WHITESPACE));
        }
        switch ($side) {
            case 'l': // Left trim.
                $string = preg_replace('/^(?:'.$whitespace.')+/u', '', $string);
                break; // Break switch handler.

            case 'r': // Right trim.
                $string = preg_replace('/(?:'.$whitespace.')+$/u', '', $string);
                break; // Break switch handler.

            default: // Both sides.
                $string = preg_replace('/^(?:'.$whitespace.')+|(?:'.$whitespace.')+$/u', '', $string);
                break; // Break switch handler.
        }
        return c\mb_trim($string, $chars, $extra_chars, $side);
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
    public function l($value, string $chars = '', string $extra_chars = '')
    {
        return $this->__invoke($value, $chars, $extra_chars, 'l');
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
    public function r($value, string $chars = '', string $extra_chars = '')
    {
        return $this->__invoke($value, $chars, $extra_chars, 'r');
    }
}
