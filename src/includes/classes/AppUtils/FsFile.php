<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * FS file utilities.
 *
 * @since 150424 Initial release.
 */
class FsFile extends Classes\AbsBase
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
    public function ext(string $path): string
    {
        if (mb_substr($path, -1) === '/') {
            return ''; // Directory.
        }
        if (!($basename = basename($path))) {
            return ''; // Nothing.
        }
        if (!($ext = mb_strrchr($basename, '.'))) {
            return ''; // Nothing.
        }
        $ext = $this->Utils->Trim->l($ext, '.');
        $ext = mb_strtolower($ext);

        return $ext;
    }
}
