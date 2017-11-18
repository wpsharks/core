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
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
//
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
//
use function assert as debug;
use function get_defined_vars as vars;
//
use RedeyeVentures\GeoPattern\GeoPattern;

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
        $data = str_replace(' ', '+', $data_url);
        $f100 = mb_substr($data, 0, 100);

        if (mb_strpos($f100, 'data:image/x-icon;base64,') === 0) {
            $extension = 'ico';
        } elseif (mb_strpos($f100, 'data:image/icon;base64,') === 0) {
            $extension = 'ico';
        } elseif (mb_strpos($f100, 'data:image/ico;base64,') === 0) {
            $extension = 'ico';
        } elseif (mb_strpos($f100, 'data:image/gif;base64,') === 0) {
            $extension = 'gif';
        } elseif (mb_strpos($f100, 'data:image/jpg;base64,') === 0) {
            $extension = 'jpg';
        } elseif (mb_strpos($f100, 'data:image/jpeg;base64,') === 0) {
            $extension = 'jpg';
        } elseif (mb_strpos($f100, 'data:image/png;base64,') === 0) {
            $extension = 'png';
        } elseif (mb_strpos($f100, 'data:image/svg+xml;base64,') === 0) {
            $extension = 'svg';
        } elseif (mb_strpos($f100, 'data:image/webp;base64,') === 0) {
            $extension = 'webp';
        } else {
            return []; // Not possible.
        }
        if (!($raw_image_data = mb_substr($data, mb_strpos($data, ',') + 1))) {
            return []; // Decode failure.
        } elseif (!($raw_image_data = (string) base64_decode($raw_image_data, true))) {
            return []; // Decode failure.
        }
        return compact('raw_image_data', 'extension');
    }

    /**
     * Convert SVG to PNG.
     *
     * @since 17xxxx SVG converter.
     *
     * @param string $file SVG file path.
     * @param array  $args Any behavioral args.
     *
     * @return bool True on success.
     *
     * @see {@link compressPng()}
     */
    public function svgToPng(string $file, array $args = []): bool
    {
        if (!class_exists('Imagick')) {
            return false; // Not possible.
        } elseif (!$file || !is_file($file)) {
            return false; // Not possible.
        }
        $png_file = preg_replace('/\.[^.]+$/ui', '.png', $file);

        $default_args = [
            'width'    => 0,
            'height'   => 0,
            'texture'  => false,
            'filter'   => \Imagick::FILTER_LANCZOS,
            'blur'     => 1,
            'bestfit'  => false,

            'output_file' => $png_file,
            'bits'        => 32,
        ];
        $args += $default_args; // Merge w/ defaults.

        $args['width']   = (int) $args['width'];
        $args['height']  = (int) $args['height'];
        $args['texture'] = (bool) $args['texture'];
        $args['filter']  = (int) $args['filter'];
        $args['blur']    = (int) $args['blur'];
        $args['bestfit'] = (bool) $args['bestfit'];

        $args['output_file'] = (string) $args['output_file'];
        $args['output_file'] = $args['output_file'] ?: $png_file;
        $args['bits']        = (int) $args['bits'];

        $format      = 'png'.$args['bits'];
        $resize_args = [$args['width'], $args['height'], $args['filter'], $args['blur'], $args['bestfit']];

        if ($this->c::fileExt($args['output_file']) !== 'png') {
            return false; // Wrong extension.
        }
        try { // Catch exceptions.
            $svg   = new \Imagick();
            $trans = new \ImagickPixel('transparent');

            $svg->setBackgroundColor($trans);
            $svg->readImage('svg:'.$file);

            if ($args['width'] && $args['texture']) {
                $png = new \Imagick();
                $png->newImage($args['width'], $args['height'], $trans, $format);
                ($png = $png->textureImage($svg))->writeImage($args['output_file']);
            } elseif ($args['width']) {
                $svg->resizeImage(...$resize_args);
                $svg->writeImage($format.':'.$args['output_file']);
            } else {
                $svg->writeImage($format.':'.$args['output_file']);
            }
        } catch (\Throwable $Exception) {
            @unlink($args['output_file']);
            return false;
        }
        return true;
    }

    /**
     * Compress PNG image.
     *
     * @since 161011 Initial release.
     *
     * @param string $file PNG file path.
     * @param array  $args Any behavioral args.
     *
     * @return bool True on success.
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

        if ($this->c::fileExt($args['output_file']) !== 'png') {
            return false; // Wrong extension.
        }
        $esc_file        = $this->c::escShellArg($file);
        $esc_output_file = $this->c::escShellArg($args['output_file']);
        $esc_min         = $this->c::escShellArg($args['min_quality']);
        $esc_max         = $this->c::escShellArg($args['max_quality']);

        exec('pngquant --quality='.$esc_min.'-'.$esc_max.' --force --output='.$esc_output_file.' '.$esc_file, $output, $status);

        return $status === 0 || $status === 99;
    }

    /**
     * Geo-pattern generator.
     *
     * @since 17xxxx Geo-patterns.
     *
     * @param string $file New SVG file path.
     * @param array  $args Any behavioral args.
     *
     * @return bool True on success.
     */
    public function geoPattern(string $file, array $args = []): bool
    {
        $default_args = [
            'string' => $this->c::uniqueId(),
            'for'    => '', // Alias of string.
        ];
        $args += $default_args; // Merge defaults.

        if ($this->c::fileExt($file) !== 'svg') {
            return false; // Wrong extension.
        }
        $args['string'] = (string) $args['string'];
        $args['for']    = (string) $args['for'];

        $args['string'] = $args['for'] ?: $args['string'];
        unset($args['for']); // Housekeeping.

        $data  = (new GeoPattern($args))->toSVG();
        return file_put_contents($file, $data) !== false;
    }

    /**
     * Geo-pattern thumbnail.
     *
     * @since 17xxxx Geo-patterns.
     *
     * @param string $file New PNG file path.
     * @param array  $args Any behavioral args.
     *
     * @return bool True on success.
     */
    public function geoPatternThumbnail(string $file, array $args = []): bool
    {
        $default_args = [
            'width'       => 512,
            'height'      => 256,
            'texture'     => true,
            'output_file' => $file,
        ];
        $args += $default_args; // Merge defaults.

        if ($this->c::fileExt($file) !== 'png') {
            return false; // Wrong extension.
        }
        $svg_file = preg_replace('/\.[^.]+$/ui', '.svg', $file);

        if (!$this->geoPattern($svg_file, $args)) {
            return false;
        }
        if (!$this->svgToPng($svg_file, $args)) {
            @unlink($svg_file);
            return false;
        }
        @unlink($svg_file);
        return true;
    }
}
