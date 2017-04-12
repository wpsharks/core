<?php
/**
 * Clip utilities.
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
 * Clip utilities.
 *
 * @since 150424 Initial release.
 */
class Clip extends Classes\Core\Base\Core
{
    /**
     * Clips string(s) to X chars deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed  $value      Any input value.
     * @param int    $max_length Defaults to `80`.
     * @param bool   $force_more Defaults to `false`.
     * @param string $more       Custom more link or text.
     * @param bool   $for_html   Output for HTML markup?
     *
     * @return string|array|object Clipped value.
     */
    public function __invoke($value, int $max_length = 80, bool $force_more = false, string $more = ' […]', bool $for_html = false)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($_value, $max_length, $force_more, $more, $for_html);
            } // unset($_key, $_value); // Housekeeping.
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Empty.
        }
        $is_html_more = $more && $this->c::isHtml($more);
        $for_html     = $for_html || $is_html_more;

        $more_length = mb_strlen($is_html_more ? strip_tags($more) : $more);
        $max_length  = max($more_length + 1, $max_length);

        $string = $this->c::htmlToText($string, ['br2nl' => false]);
        // The string is now text, which means `&<>` are unescaped.

        if (mb_strlen($string) > $max_length) {
            $string = (string) mb_substr($string, 0, $max_length - $more_length);
            $string = $for_html ? $this->c::escHtml($string) : $string;
            $string .= $more; // Add whatever `$more` is now.
        } elseif ($force_more && mb_strlen($string) + $more_length > $max_length) {
            $string = (string) mb_substr($string, 0, $max_length - $more_length);
            $string = $for_html ? $this->c::escHtml($string) : $string;
            $string .= $more; // Add whatever `$more` is now.
        } else {
            $string = $for_html ? $this->c::escHtml($string) : $string;
            $string .= $force_more ? $more : ''; // Maybe add `$more`.
        }
        return $string;
    }
}
