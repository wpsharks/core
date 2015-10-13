<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Interfaces;

/**
 * HTML trim utilities.
 *
 * @since 150424 Initial release.
 */
class HtmlTrim extends AbsBase implements Interfaces\HtmlConstants
{
    protected $Trim;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(
        Trim $Trim
    ) {
        parent::__construct();

        $this->Trim = $Trim;
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
    public function __invoke($value, string $chars = '', string $extra_chars = '', string $side = '')
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($_value, $chars, $extra_chars, $side);
            }
            unset($_key, $_value); // Housekeeping.

            return $this->Trim($value, $chars, $extra_chars, $side);
        }
        $value = (string) $value;
        $side  = strtolower($side);

        if (is_null($whitespace = &$this->staticKey(__FUNCTION__.'_whitespace'))) {
            $whitespace = implode('|', array_keys($this::HTML_WHITESPACE));
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
        return $this->Trim($value, $chars, $extra_chars, $side);
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
    public function left($value, string $chars = '', string $extra_chars = '')
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
    public function right($value, string $chars = '', string $extra_chars = '')
    {
        return $this->__invoke($value, $chars, $extra_chars, 'r');
    }
}
