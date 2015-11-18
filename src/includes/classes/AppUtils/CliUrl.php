<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;

/**
 * CLI URL utilities.
 *
 * @since 150424 Initial release.
 */
class CliUrl extends Classes\AbsBase
{
    /**
     * Open a URL from the command line.
     *
     * @since 150424 Initial release.
     *
     * @param string $url The URL to open.
     */
    public function open(string $url)
    {
        if (!($url = $this->Utils->Trim($url))) {
            return; // Not possible.
        }
        $url_arg = escapeshellarg($url);

        if ($this->Utils->Os->isMac()) {
            `open $url_arg`;
        } elseif ($this->Utils->Os->isLinux()) {
            `xdg-open $url_arg`;
        } elseif ($this->Utils->Os->isWindows()) {
            `start $url_arg`;
        } else {
            throw new Exception('Unable to open <'.$url.'>. Unsupported operating system.');
        }
    }
}
