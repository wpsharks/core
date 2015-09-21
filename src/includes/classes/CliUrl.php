<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * CLI URL utilities.
 *
 * @since 150424 Initial release.
 */
class CliUrl extends AbsBase
{
    protected $Os;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(Os $Os)
    {
        parent::__construct();

        $this->Os = $Os;
    }

    /**
     * Open a URL from the command line.
     *
     * @since 150424 Initial release.
     *
     * @param string $url The URL to open.
     */
    public function open($url)
    {
        if (!($url = trim((string) $url))) {
            return; // Not possible.
        }
        $url_arg = escapeshellarg($url);

        if ($this->Os->isMac()) {
            `open $url_arg`;
        } elseif ($this->Os->isLinux()) {
            `xdg-open $url_arg`;
        } elseif ($this->Os->isWindows()) {
            `start $url_arg`;
        } else {
            throw new \Exception('Unable to open <'.$url.'>. Unsupported operating system.');
        }
    }
}
