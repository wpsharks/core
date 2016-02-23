<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * OS utilities.
 *
 * @since 150424 Initial release.
 */
class Os extends Classes\Core
{
    /**
     * A Unix environment?
     *
     * @since 150424 Initial release.
     *
     * @return bool `TRUE` if in a Unix environment.
     */
    public function isUnix(): bool
    {
        return !$this->isWindows();
    }

    /**
     * A Linux environment?
     *
     * @since 150424 Initial release.
     *
     * @return bool `TRUE` if in a Linux environment.
     */
    public function isLinux(): bool
    {
        return mb_stripos(PHP_OS, 'linux') === 0;
    }

    /**
     * A Mac environment?
     *
     * @since 150424 Initial release.
     *
     * @return bool `TRUE` if in a Mac environment.
     */
    public function isMac(): bool
    {
        return mb_stripos(PHP_OS, 'darwin') === 0;
    }

    /**
     * A Windows environment?
     *
     * @since 150424 Initial release.
     *
     * @return bool `TRUE` if in a Windows environment.
     */
    public function isWindows(): bool
    {
        return mb_stripos(PHP_OS, 'win') === 0;
    }
}
