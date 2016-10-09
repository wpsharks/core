<?php
/**
 * Color utilities.
 *
 * @author @jaswsinc
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
 * Color utilities.
 *
 * @since 161010 Color utilities.
 */
class Colors extends Classes\Core\Base\Core
{
    /**
     * Contrasting foreground color.
     *
     * @since 161010 Color utilities.
     *
     * @param string $bg_hex Background color.
     *
     * @return string Suggested foreground color.
     *
     * @see https://24ways.org/2010/calculating-color-contrast/
     */
    public function contrastingFg(string $bg_hex): string
    {
        $bg_hex = ltrim($bg_hex, '#');

        if (!isset($bg_hex[5])) {
            $bg_hex .= $bg_hex; // e.g., `fff` * 2.

            if (!isset($bg_hex[5])) {
                return '#000'; // Failure.
            }
        }
        $r = hexdec(substr($bg_hex, 0, 2));
        $g = hexdec(substr($bg_hex, 2, 2));
        $b = hexdec(substr($bg_hex, 4, 2));

        $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

        return $yiq >= 128 ? '#000' : '#fff';
    }
}
