<?php
namespace WebSharks\Core\Traits;

/**
 * Clip utilities.
 *
 * @since 150424 Initial release.
 */
trait ClipUtils
{
    abstract protected function htmlToText($value, array $args = array());

    /**
     * Clips string(s) to X chars deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value          Any input value.
     * @param int   $max_length     Defaults to a value of `80`.
     * @param bool  $force_ellipsis Defaults to a value of `FALSE`.
     *
     * @return string|array|object Clipped value.
     */
    protected function clip($value, $max_length = 80, $force_ellipsis = false)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->clip($_value, $max_length, $force_ellipsis);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Empty.
        }
        $max_length = max(4, (integer) $max_length);
        $string     = $this->htmlToText($string, ['br2nl' => false]);

        if (strlen($string) > $max_length) {
            $string = (string) substr($string, 0, $max_length - 3).'...';
        } elseif ($force_ellipsis && strlen($string) + 3 > $max_length) {
            $string = (string) substr($string, 0, $max_length - 3).'...';
        } else {
            $string .= $force_ellipsis ? '...' : '';
        }
        return $string;
    }

    /**
     * Mid-clips string(s) to X chars deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value      Any input value.
     * @param int   $max_length Defaults to a value of `80`.
     *
     * @return string|array|object Mid-clipped value.
     */
    protected function clipMid($value, $max_length = 80)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->clipMid($_value, $max_length);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Empty.
        }
        $max_length = max(4, (integer) $max_length);
        $string     = $this->htmlToText($string, ['br2nl' => false]);

        if (strlen($string) <= $max_length) {
            return $string; // Nothing to do.
        }
        $full_string     = $string;
        $half_max_length = floor($max_length / 2);

        $first_clip = $half_max_length - 3;
        $string     = $first_clip >= 1 // Something?
            ? substr($full_string, 0, $first_clip).'...'
            : '...'; // Ellipsis only.

        $second_clip = strlen($full_string) - ($max_length - strlen($string));
        $string .= $second_clip >= 0 && $second_clip >= $first_clip
            ? substr($full_string, $second_clip) : '';

        return $string;
    }
}
