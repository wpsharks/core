<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * CLI URL utilities.
 *
 * @since 150424 Initial release.
 */
class CliUrl extends Classes\AbsBase
{
    /**
     * Class constructor.
     *
     * @since 150424 Initial release.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        if (!$this->Utils->Cli->is()) {
            throw new Exception('Requires CLI mode.');
        }
    }

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
