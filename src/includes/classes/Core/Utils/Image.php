<?php
/**
 * Image utilities.
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
 * Image utilities.
 *
 * @since 161011 Compression.
 */
class Image extends Classes\Core\Base\Core
{
    /**
     * Decode image data URL.
     *
     * @since 161011 Initial release.
     *
     * @param string $data_url Data URL.
     *
     * @return array `[raw_image_data, extension]`.
     */
    public function decodeDataUrl(string $data_url): array
    {
        if (mb_strpos($data_url, 'data:') !== 0) {
            return []; // Not applicable.
        }
        $data      = str_replace(' ', '+', $data_url);
        $data_f100 = mb_substr($data, 0, 100);

        if (mb_strpos($data_f100, 'data:image/x-icon;base64,') === 0
            || mb_strpos($data_f100, 'data:image/icon;base64,') === 0
            || mb_strpos($data_f100, 'data:image/ico;base64,') === 0) {
            $extension = 'ico';
        } elseif (mb_strpos($data_f100, 'data:image/gif;base64,') === 0) {
            $extension = 'gif';
        } elseif (mb_strpos($data_f100, 'data:image/jpg;base64,') === 0
            || mb_strpos($data, 'data:image/jpeg;base64,') === 0) {
            $extension = 'jpg';
        } elseif (mb_strpos($data_f100, 'data:image/png;base64,') === 0) {
            $extension = 'png';
        } elseif (mb_strpos($data_f100, 'data:image/webp;base64,') === 0) {
            $extension = 'webp';
        } else { // Invalid; unknown MIME type.
            return []; // Not possible.
        }
        if (!($raw_image_data = mb_substr($data, mb_strpos($data, ',') + 1))
            || !($raw_image_data = (string) base64_decode($raw_image_data, true))) {
            return []; // Decode failure.
        }
        return compact('raw_image_data', 'extension');
    }

    /**
     * Compress PNG image.
     *
     * @since 161011 Initial release.
     *
     * @param string $file PNG file path.
     * @param array  $args Any behavioral args.
     *
     * @return bool True if compression successfull.
     *
     * @see https://pngquant.org/php.html
     */
    public function compressPng(string $file, array $args = []): bool
    {
        if (!$this->c::canCallFunc('exec')) {
            return false; // Not possible.
        } elseif (!$file || !is_file($file)) {
            return false; // Not possible.
        }
        $default_args = [
            'min_quality' => 60,
            'max_quality' => 90,
            'output_file' => $file,
        ];
        $args += $default_args; // Merge w/ defaults.

        $args['min_quality'] = min(100, max(0, (int) $args['min_quality']));
        $args['max_quality'] = min(100, max(0, (int) $args['max_quality']));

        $args['output_file'] = (string) $args['output_file'];
        $args['output_file'] = $args['output_file'] ?: $file;

        $esc_file        = $this->c::escShellArg($file);
        $esc_output_file = $this->c::escShellArg($args['output_file']);
        $esc_min         = $this->c::escShellArg($args['min_quality']);
        $esc_max         = $this->c::escShellArg($args['max_quality']);

        exec('pngquant --quality='.$esc_min.'-'.$esc_max.' --force --output='.$esc_output_file.' '.$esc_file, $output, $status);

        return $status === 0 || $status === 99;
    }
}
