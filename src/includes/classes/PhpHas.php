<?php
namespace WebSharks\Core\Classes;

/**
 * PHP has-check utilities.
 *
 * @since 150424 Initial release.
 */
class PhpHas extends AbsBase
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
     * Checks if a PHP extension is loaded.
     *
     * @since 150424 Initial release.
     *
     * @param string $extension A PHP extension slug.
     *
     * @return bool `TRUE` if the extension is loaded.
     */
    public function extension($extension)
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
    public function callableFunction($function)
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
