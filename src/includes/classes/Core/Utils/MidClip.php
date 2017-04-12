<?php
/**
 * Mid clip utilities.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Mid clip utilities.
 *
 * @since 150424 Initial release.
 */
class MidClip extends Classes\Core\Base\Core
{
    /**
     * Mid-clips string(s) to X chars deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed  $value      Any input value.
     * @param int    $max_length Defaults to `80`.
     * @param string $more       Custom more link or text.
     * @param bool   $for_html   Output for HTML markup?
     *
     * @return string|array|object Mid-clipped value.
     */
    public function __invoke($value, int $max_length = 80, string $more = '…', bool $for_html = false)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($_value, $max_length, $more, $for_html);
            } // unset($_key, $_value);
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Empty.
        }
        $is_html_more = $more && $this->c::isHtml($more);
        $for_html     = $for_html || $is_html_more;

        $more_length     = mb_strlen($is_html_more ? strip_tags($more) : $more);
        $max_length      = max($more_length + 1, $max_length);
        $half_max_length = (int) floor($max_length / 2);

        $string = $this->c::htmlToText($string, ['br2nl' => false]);
        // The string is now text, which means `&<>` are unescaped.
        $full_string = $string; // Remember full string.

        if (mb_strlen($string) <= $max_length) {
            return $string = $for_html ? $this->c::escHtml($string) : $string;
        }
        $first_clip = $half_max_length - $more_length;
        $string     = $first_clip >= 1 ? mb_substr($full_string, 0, $first_clip).'|%%more%%|' : '|%%more%%|';

        $second_clip = mb_strlen($full_string) - $half_max_length;
        $string .= $second_clip >= 0 && $second_clip >= $first_clip ? mb_substr($full_string, $second_clip) : '';

        $string = $for_html ? $this->c::escHtml($string) : $string;
        $string = str_replace('|%%more%%|', $more, $string);

        return $string;
    }
}
