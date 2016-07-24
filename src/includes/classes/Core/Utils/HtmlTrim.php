<?php
/**
 * HTML trim utilities.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * HTML trim utilities.
 *
 * @since 150424 Initial release.
 */
class HtmlTrim extends Classes\Core\Base\Core implements Interfaces\HtmlConstants
{
    /**
     * Trims HTML content deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed  $value     Any input value.
     * @param string $directive Default is empty (all whitespace).
     *                          `vertical` trims only vertical whitespace.
     *                          `horizontal` trims only horizontal whitespace.
     * @param string $side      Optional; one of `l` or `r`.
     *
     * @return string|array|object Trimmed string, array, object.
     */
    public function __invoke($value, string $directive = '', string $side = '')
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($_value, $directive, $side);
            } // unset($_key, $_value); // Housekeeping.
            return $value;
        }
        $string = (string) $value; // Force string.

        if (!isset($string[0])) {
            return $string; // Nothing to do.
        }
        if ($directive === 'vertical') {
            if (($_vertical_whitespace = &$this->cacheKey(__FUNCTION__.'_vertical_whitespace')) === null) {
                $_vertical_whitespace = implode('|', $this::HTML_VERTICAL_WHITESPACE);
            } // Only trim vertical whitespace in this case.
            $whitespace = $_vertical_whitespace; // Needed below.
            //
        } elseif ($directive === 'horizontal') {
            if (($_horizontal_whitespace = &$this->cacheKey(__FUNCTION__.'_horizontal_whitespace')) === null) {
                $_horizontal_whitespace = implode('|', $this::HTML_HORIZONTAL_WHITESPACE);
            } // Only trim vertical whitespace in this case.
            $whitespace = $_horizontal_whitespace; // Needed below.
            //
        } else { // Trim all whitespace.
            if (($_whitespace = &$this->cacheKey(__FUNCTION__.'_whitespace')) === null) {
                $_whitespace = implode('|', $this::HTML_WHITESPACE);
            } // Trims all whitespace in this case (default).
            $whitespace = $_whitespace; // Needed below.
        }
        switch ($side) {
            case 'l': // Left trim.
                $string = preg_replace('/^(?:'.$whitespace.')+/u', '', $string);
                break;

            case 'r': // Right trim.
                $string = preg_replace('/(?:'.$whitespace.')+$/u', '', $string);
                break;

            default: // Both sides.
                $string = preg_replace('/^(?:'.$whitespace.')+|(?:'.$whitespace.')+$/u', '', $string);
                break;
        }
        return $string; // Nothing more to do here.
    }

    /**
     * Left-trims an HTML content string.
     *
     * @since 150424 Initial release.
     *
     * @param mixed  $value     Any input value.
     * @param string $directive Default is empty (all whitespace).
     *                          `vertical` trims only vertical whitespace.
     *                          `horizontal` trims only horizontal whitespace.
     *
     * @return string|array|object Trimmed string, array, object.
     */
    public function l($value, string $directive = '')
    {
        return $this->__invoke($value, $directive, 'l');
    }

    /**
     * Right-trims an HTML content string.
     *
     * @since 150424 Initial release.
     *
     * @param mixed  $value     Any input value.
     * @param string $directive Default is empty (all whitespace).
     *                          `vertical` trims only vertical whitespace.
     *                          `horizontal` trims only horizontal whitespace.
     *
     * @return string|array|object Trimmed string, array, object.
     */
    public function r($value, string $directive = '')
    {
        return $this->__invoke($value, $directive, 'r');
    }
}
