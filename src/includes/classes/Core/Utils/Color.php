<?php
/**
 * Color utilities.
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
 * Color utilities.
 *
 * @since 161010 Color utilities.
 */
class Color extends Classes\Core\Base\Core
{
    /**
     * SHA-1 color generation.
     *
     * @since 161012 Color utilities.
     *
     * @param string $string String to hash.
     * @param float  $adjust See {@link adjusLuminosity()}.
     *
     * @return string Auto-generated color with X luminosity.
     */
    public function sha1(string $string, float $adjust = 0): string
    {
        $hex        = '#'.mb_substr(sha1($string), 0, 6);
        return $hex = $adjust ? $this->adjustLuminosity($hex, $adjust) : $hex;
    }

    /**
     * Adjust luminosity.
     *
     * @since 161010 Color utilities.
     *
     * @param string $hex    Hex color code to adjust.
     * @param float  $adjust `-0.5` (50% darker), `0.5` (50% ligher).
     *
     * @return string Adjusted hex color, +- luminosity.
     *
     * @see https://www.sitepoint.com/javascript-generate-lighter-darker-color/
     */
    public function adjustLuminosity(string $hex, float $adjust): string
    {
        $hex = $this->cleanHex($hex);

        for ($adjusted_hex = '', $_dec, $_hex, $_i = 0; $_i < 3; ++$_i) {
            $_dec = hexdec(mb_substr($hex, $_i * 2, 2));
            $_hex = dechex(round(min(max(0, $_dec + ($_dec * $adjust)), 255)));
            $adjusted_hex .= $this->c::mbStrPad($_hex, 2, '0');
        } // unset($_dec, $_hex, $_i); // Housekeeping.

        return '#'.$adjusted_hex;
    }

    /**
     * Contrasting B/W color.
     *
     * @since 161010 Color utilities.
     *
     * @param string $hex Hex color code.
     *
     * @return string Suggested B/W contrasting color.
     *
     * @see https://24ways.org/2010/calculating-color-contrast/
     */
    public function contrastingBw(string $hex): string
    {
        $hex = $this->cleanHex($hex);

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $yiq = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

        return $yiq >= 128 ? '#000000' : '#ffffff';
    }

    /**
     * Cleans a hex color.
     *
     * @since 161010 Color utilities.
     *
     * @param string $hex Hex color.
     *
     * @return string Clean hex color.
     */
    public function cleanHex(string $hex): string
    {
        $hex = ltrim($hex, '#');

        if (!isset($hex[5])) {
            $hex = preg_replace('/(.)/u', '$1$1', $hex);
            $hex = $this->c::mbStrPad($hex, 6, '0');
        }
        return $hex;
    }
}
