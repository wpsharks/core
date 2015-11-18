<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;

/**
 * Clipper utilities.
 *
 * @since 150424 Initial release.
 */
class Clip extends Classes\AbsBase
{
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
    public function __invoke($value, int $max_length = 80, bool $force_ellipsis = false)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($_value, $max_length, $force_ellipsis);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Empty.
        }
        $max_length = max(4, $max_length);
        $string     = $this->Utils->HtmlConvert->toText($string, ['br2nl' => false]);

        if (mb_strlen($string) > $max_length) {
            $string = (string) mb_substr($string, 0, $max_length - 3).'...';
        } elseif ($force_ellipsis && mb_strlen($string) + 3 > $max_length) {
            $string = (string) mb_substr($string, 0, $max_length - 3).'...';
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
    public function mid($value, int $max_length = 80)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->mid($_value, $max_length);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Empty.
        }
        $max_length      = max(4, $max_length);
        $half_max_length = floor($max_length / 2);
        $string          = $this->Utils->HtmlConvert->toText($string, ['br2nl' => false]);
        $full_string     = $string; // Remember full string.

        if (mb_strlen($string) <= $max_length) {
            return $string; // Nothing to do.
        }
        $first_clip = $half_max_length - 3;
        $string     = $first_clip >= 1 ? mb_substr($full_string, 0, $first_clip).'...' : '...';

        $second_clip = mb_strlen($full_string) - ($max_length - mb_strlen($string));
        $string .= $second_clip >= 0 && $second_clip >= $first_clip ? mb_substr($full_string, $second_clip) : '';

        return $string;
    }
}
