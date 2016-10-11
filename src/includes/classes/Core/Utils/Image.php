<?php
/**
 * Image utilities.
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
 * Image utilities.
 *
 * @since 161011 Compression.
 */
class Image extends Classes\Core\Base\Core
{
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
