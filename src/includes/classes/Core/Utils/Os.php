<?php
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
 * OS utilities.
 *
 * @since 150424 Initial release.
 */
class Os extends Classes\Core\Base\Core
{
    /**
     * A nix OS?
     *
     * @since 150424 Initial release.
     *
     * @return bool True or false.
     */
    public function isNix(): bool
    {
        return !$this->isWindows();
    }

    /**
     * A linux OS?
     *
     * @since 150424 Initial release.
     *
     * @return bool True or false.
     */
    public function isLinux(): bool
    {
        return mb_stripos(PHP_OS, 'linux') === 0;
    }

    /**
     * A mac OS?
     *
     * @since 150424 Initial release.
     *
     * @return bool True or false.
     */
    public function isMac(): bool
    {
        return mb_stripos(PHP_OS, 'darwin') === 0;
    }

    /**
     * A windows OS?
     *
     * @since 150424 Initial release.
     *
     * @return bool True or false.
     */
    public function isWindows(): bool
    {
        return mb_stripos(PHP_OS, 'win') === 0;
    }
}
