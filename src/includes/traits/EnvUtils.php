<?php
namespace WebSharks\Core\Traits;

/**
 * Environment utilities.
 *
 * @since 150424 Initial release.
 */
trait EnvUtils
{
    abstract protected function fsSizeAbbrBytes();
    abstract protected function urlCurrentHost($no_port = false);
    abstract protected function &staticKey($function, $args = array());

    /**
     * Current request over SSL?
     *
     * @since 150424 Initial release.
     *
     * @return bool `TRUE` if over SSL.
     */
    protected function envIsSsl()
    {
        if (!is_null($is = &$this->staticKey(__FUNCTION__))) {
            return $is; // Cached this already.
        }
        if (!empty($_SERVER['SERVER_PORT'])) {
            if ((integer) $_SERVER['SERVER_PORT'] === 443) {
                return ($is = true);
            }
        }
        if (!empty($_SERVER['HTTPS'])) {
            if (filter_var($_SERVER['HTTPS'], FILTER_VALIDATE_BOOLEAN)) {
                return ($is = true);
            }
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
            if (strcasecmp($_SERVER['HTTP_X_FORWARDED_PROTO'], 'https') === 0) {
                return ($is = true);
            }
        }
        return ($is = false);
    }

    /**
     * Running in a command line interface?
     *
     * @since 150424 Initial release.
     *
     * @return bool `TRUE` if running in a command line interface.
     */
    protected function envIsCli()
    {
        if (!is_null($is = &$this->staticKey(__FUNCTION__))) {
            return $is; // Cached this already.
        }
        if (strcasecmp(PHP_SAPI, 'cli') === 0) {
            return ($is = true);
        }
        return ($is = false);
    }

    /**
     * In a localhost environment?
     *
     * @since 150424 Initial release.
     *
     * @return bool `TRUE` if in a localhost environment.
     */
    protected function envIsLocalhost()
    {
        if (!is_null($is = &$this->staticKey(__FUNCTION__))) {
            return $is; // Cached this already.
        }
        if (defined('LOCALHOST') && LOCALHOST) {
            return ($is = true);
        }
        if (preg_match('/\b(?:localhost|127\.0\.0\.1)\b/i', $this->urlCurrentHost())) {
            return ($is = true);
        }
        return ($is = false);
    }

    /**
     * A Unix environment?
     *
     * @since 150424 Initial release.
     *
     * @return bool `TRUE` if in a Unix environment.
     */
    protected function envIsUnix()
    {
        if (!is_null($is = &$this->staticKey(__FUNCTION__))) {
            return $is; // Cached this already.
        }
        if (stripos(PHP_OS, 'win') !== 0) {
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
    protected function envIsWindows()
    {
        if (!is_null($is = &$this->staticKey(__FUNCTION__))) {
            return $is; // Cached this already.
        }
        if (stripos(PHP_OS, 'win') === 0) {
            return ($is = true);
        }
        return ($is = false);
    }

    /**
     * Prepares for output delivery.
     *
     * @since 150424 Initial release.
     */
    protected function envPrepForOutput()
    {
        @set_time_limit(0);

        @ini_set('zlib.output_compression', 0);
        if ($this->envHasFunction('apache_setenv')) {
            @apache_setenv('no-gzip', '1');
        }
        while (@ob_end_clean()) {
            // Clean buffers :-)
        }
    }

    /**
     * Doing an exit?
     *
     * @since 150424 Initial release.
     *
     * @param bool|null $doing Pass this to set the value.
     *
     * @return bool `TRUE` if doing an exit.
     */
    protected function envDoingExit($doing = null)
    {
        if (isset($doing)) {
            $GLOBALS[__NAMESPACE__.'_'.__FUNCTION__] = (boolean) $doing;
        }
        return !empty($GLOBALS[__NAMESPACE__.'_'.__FUNCTION__]);
    }

    /**
     * Maxmizes available memory.
     *
     * @since 150424 Initial release.
     */
    protected function envMaximizeMemory($max = '256M')
    {
        @ini_set('memory_limit', (string) $max);
    }

    /**
     * Max execution time.
     *
     * @since 150424 Initial release.
     *
     * @return int Max execution time; in seconds.
     */
    protected function envMaxExecutionTime()
    {
        return (integer) ini_get('max_execution_time');
    }

    /**
     * Max allowed file upload size.
     *
     * @since 150424 Initial release.
     *
     * @return float A floating point number.
     */
    protected function envMaxUploadSize()
    {
        $limits = array(PHP_INT_MAX); // Initialize.

        if (($max_upload_size = ini_get('upload_max_filesize'))) {
            $limits[] = $this->fsSizeAbbrBytes($max_upload_size);
        }
        if (($post_max_size = ini_get('post_max_size'))) {
            $limits[] = $this->fsSizeAbbrBytes($post_max_size);
        }
        if (($memory_limit = ini_get('memory_limit'))) {
            $limits[] = $this->fsSizeAbbrBytes($memory_limit);
        }
        return ($max = min($limits));
    }

    /**
     * Checks if a PHP extension is loaded.
     *
     * @since 150424 Initial release.
     *
     * @param string $extension A PHP extension slug.
     *
     * @return bool `TRUE` if the extension is loaded.
     */
    protected function envHasExtension($extension)
    {
        $extension = (string) $extension;

        if (!is_null($has = &$self->staticKey(__FUNCTION__, $extension))) {
            return $has; // Already cached this.
        }
        return ($has = (boolean) extension_loaded($extension));
    }

    /**
     * Is a particular function possible in every way?
     *
     * @since 150424 Initial release.
     *
     * @param string $function A PHP function (or user function) to check.
     *
     * @return string `TRUE` if the function is possible.
     *
     * @note This checks (among other things) if the function exists and that it's callable.
     *    It also checks the currently configured `disable_functions` and `suhosin.executor.func.blacklist`.
     */
    protected function envHasFunction($function)
    {
        $function = (string) $function;

        if (!is_null($has = &$self->staticKey(__FUNCTION__, $function))) {
            return $has; // Already cached this.
        }
        if (is_null($disabled_functions = &$self->staticKey(__FUNCTION__.'_disabled_functions'))) {
            $disabled_functions = array(); // Initialize disabled/blacklisted functions.

            if (($disable_functions = trim(ini_get('disable_functions')))) {
                $disabled_functions = array_merge($disabled_functions, preg_split('/[\s;,]+/', strtolower($disable_functions), null, PREG_SPLIT_NO_EMPTY));
            }
            if (($blacklist_functions = trim(ini_get('suhosin.executor.func.blacklist')))) {
                $disabled_functions = array_merge($disabled_functions, preg_split('/[\s;,]+/', strtolower($blacklist_functions), null, PREG_SPLIT_NO_EMPTY));
            }
        }
        if (!function_exists($function) || !is_callable($function)) {
            return ($has = false); // Not possible.
        }
        if ($disabled_functions && in_array(strtolower($function), $disabled_functions, true)) {
            return ($has = false); // Not possible.
        }
        return ($has = true);
    }
}
