<?php
/**
 * File ext utilities.
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

/**
 * File ext utilities.
 *
 * @since 150424 Initial release.
 */
class FileExt extends Classes\Core\Base\Core
{
    /**
     * File extension.
     *
     * @since 150424 Initial release.
     *
     * @param string $path A filesystem path.
     *
     * @return string File extension; or empty string.
     */
    public function __invoke(string $path): string
    {
        if (!$path) {
            return ''; // Not possible.
        }
        if (in_array(mb_substr($path, -1), ['/', '\\', DIRECTORY_SEPARATOR], true)) {
            return ''; // Directory, not a file.
        } elseif (!($basename = basename($path))) {
            return ''; // Not possible.
        } elseif (!($ext = mb_strrchr($basename, '.'))) {
            return ''; // No extension.
        }
        $ext        = $this->c::mbLTrim($ext, '.');
        return $ext = mb_strtolower($ext);
    }

    /**
     * Set file extension.
     *
     * @since 17xxxx Extension utils.
     *
     * @param string $file Image file.
     * @param string $ext  New extension.
     *
     * @return string File w/ extension.
     */
    public function set(string $file, string $ext): string
    {
        if (!$file || !$ext) {
            return $file; // Not possible.
        }
        return $file = preg_replace('/\.[^.]+$/ui', '', $file).'.'.$ext;
    }

    /**
     * Change file extension.
     *
     * @since 17xxxx Extension utils.
     *
     * @param string $file Image file.
     * @param string $ext  New extension.
     *
     * @return string File w/ possible extension.
     */
    public function change(string $file, string $ext): string
    {
        if (!$file || !$ext) {
            return $file; // Not possible.
        }
        return $file = preg_replace('/\.[^.]+$/ui', '.'.$ext, $file);
    }
}
