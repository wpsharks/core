<?php
/**
 * File ext utilities.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
#
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
        if (mb_substr($path, -1) === '/') {
            return ''; // Directory.
        }
        if (!($basename = basename($path))) {
            return ''; // Nothing.
        }
        if (!($ext = mb_strrchr($basename, '.'))) {
            return ''; // Nothing.
        }
        $ext = $this->c::mbLTrim($ext, '.');
        $ext = mb_strtolower($ext);

        return $ext;
    }
}
