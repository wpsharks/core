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

    /**
     * MD5 gradient image.
     *
     * @since 17xxxx Color utilities.
     *
     * @param string $md5    MD5 hash.
     * @param int    $width  Width of gradient image.
     * @param int    $height Height of gradient image.
     * @param array  $args   Any additional behavioral args.
     *
     * @return string Raw PNG image data.
     *
     * @see <https://github.com/alterebro/text2image>
     */
    public function md5GradientImage(string $md5, int $width, int $height, array $args = []): string
    {
        if (strlen($md5) !== 32) {
            $md5 = md5($md5);
        }
        $width  = min(1920, max(16, $width));
        $height = min(1080, max(16, $height));

        $default_args = [
            'steps'  => 60,
            'smooth' => true,
        ];
        $args += $default_args;
        $args['steps']  = (int) $args['steps'];
        $args['smooth'] = (bool) $args['smooth'];

        $md5_blocks = str_split($md5, 6);
        $img        = imagecreatetruecolor($width, $height);
        imagefilledrectangle($img, 0, 0, $width, $height, imagecolorallocate($img, 0, 0, 0));

        $bg_blocks = str_split($md5_blocks[4], 2);
        $bg_alpha  = floor((255 - hexdec($md5_blocks[5])) / 2);
        $bg_color  = imagecolorallocatealpha($img, hexdec($bg_blocks[0]), hexdec($bg_blocks[1]), hexdec($bg_blocks[2]), $bg_alpha);
        imagefilledrectangle($img, 0, 0, $width, $height, $bg_color);

        $gradient_steps      = min(60, max(1, $args['steps']));
        $gradient_step_alpha = 127 / $gradient_steps;
        $gradient_step_size  = (max($gradient_steps + 1, $width, $height) * 2) / $gradient_steps;

        for ($_i=0; $_i < 4; ++$_i) {
            switch ($_i) {
                case 0:
                    $_x  = 0;
                    $_y  = 0;
                    break;

                case 1:
                    $_x  = $width;
                    $_y  = 0;
                    break;

                case 2:
                    $_x  = $width;
                    $_y  = $height;
                    break;

                case 3:
                default:
                    $_x  = 0;
                    $_y  = $height;
                    break;
            }
            $_gradient_blocks = str_split($md5_blocks[$_i], 2);
            $_gradient_color  = imagecolorallocate($img, hexdec($_gradient_blocks[0]), hexdec($_gradient_blocks[1]), hexdec($_gradient_blocks[2]));
            $_gradient_colors = [0xFF & ($_gradient_color >> 0x10), 0xFF & ($_gradient_color >> 0x8), 0xFF & $_gradient_color];

            for ($_gradient_step = 0; $_gradient_step < $gradient_steps; ++$_gradient_step) {
                $_gradient_alpha_color = imagecolorallocatealpha($img, $_gradient_colors[0], $_gradient_colors[1], $_gradient_colors[2], 127 - ($gradient_step_alpha * ($gradient_steps - $_gradient_step) / 8));
                imagefilledellipse($img, $_x, $_y, $gradient_step_size * $_gradient_step, $gradient_step_size * $_gradient_step, $_gradient_alpha_color);
            } // unset($_gradient_step, $_gradient_alpha_color); // Housekeeping.
        } // unset($_i, $_x, $_y, $_gradient_blocks, $_gradient_color, $_gradient_colors);

        if ($args['smooth']) { // Apply filters?
            imagefilter($img, IMG_FILTER_GAUSSIAN_BLUR);
            imagefilter($img, IMG_FILTER_SELECTIVE_BLUR);
            imagefilter($img, IMG_FILTER_SMOOTH, -4);
        }
        ob_start();
        imagepng($img);
        $data = ob_get_clean();
        imagedestroy($img);

        return $data;
    }
}
