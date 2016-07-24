<?php
/**
 * CLI OS-based utilities.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * CLI OS-based utilities.
 *
 * @since 150424 Initial release.
 */
class CliOs extends Classes\Core\Base\Core
{
    /**
     * Class constructor.
     *
     * @since 150424 Initial release.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        if (!$this->c::isCli()) {
            throw $this->c::issue('Requires CLI mode.');
        }
    }

    /**
     * Open a URL from the command line.
     *
     * @since 150424 Initial release.
     *
     * @param string $url The URL to open.
     */
    public function openUrl(string $url)
    {
        if (!($url = $this->c::mbTrim($url))) {
            return; // Not possible.
        }
        $url_arg = escapeshellarg($url);

        if ($this->c::isMac()) {
            `open $url_arg`;
        } elseif ($this->c::isLinux()) {
            `xdg-open $url_arg`;
        } elseif ($this->c::isWindows()) {
            `start $url_arg`;
        } else {
            throw $this->c::issue('Unable to open <'.$url.'>. Unsupported operating system.');
        }
    }
}
