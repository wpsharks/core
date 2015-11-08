<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * OS utilities.
 *
 * @since 150424 Initial release.
 */
class Os extends AbsBase
{
    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * A Unix environment?
     *
     * @since 150424 Initial release.
     *
     * @return bool `TRUE` if in a Unix environment.
     */
    public function isUnix(): bool
    {
        if (!is_null($is = &$this->staticKey(__FUNCTION__))) {
            return $is; // Cached this already.
        }
        return ($is = !$this->isWindows());
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
        if (!is_null($is = &$this->staticKey(__FUNCTION__))) {
            return $is; // Cached this already.
        }
        if (mb_stripos(PHP_OS, 'linux') === 0) {
            return ($is = true);
        }
        return ($is = false);
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
        if (!is_null($is = &$this->staticKey(__FUNCTION__))) {
            return $is; // Cached this already.
        }
        if (mb_stripos(PHP_OS, 'darwin') === 0) {
            return ($is = true);
        }
        return ($is = false);
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
        if (!is_null($is = &$this->staticKey(__FUNCTION__))) {
            return $is; // Cached this already.
        }
        if (mb_stripos(PHP_OS, 'win') === 0) {
            return ($is = true);
        }
        return ($is = false);
    }
}
