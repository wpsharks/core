<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;

/**
 * PHP has-check utilities.
 *
 * @since 150424 Initial release.
 */
class PhpHas extends Classes\AbsBase
{
    /**
     * Checks if a PHP extension is loaded.
     *
     * @since 150424 Initial release.
     *
     * @param string $extension A PHP extension slug.
     *
     * @return bool `TRUE` if the extension is loaded.
     */
    public function extension(string $extension): bool
    {
        if (!is_null($has = &$this->staticKey(__FUNCTION__, $extension))) {
            return $has; // Already cached this.
        }
        return ($has = (bool) extension_loaded($extension));
    }

    /**
     * Is a particular function possible in every way?
     *
     * @since 150424 Initial release.
     *
     * @param string $function A PHP function (or user function) to check.
     *
     * @return bool `TRUE` if the function is possible.
     *
     * @note This checks (among other things) if the function exists and that it's callable.
     *    It also checks the currently configured `disable_functions` and `suhosin.executor.func.blacklist`.
     */
    public function callableFunction(string $function): bool
    {
        if (!is_null($has = &$this->staticKey(__FUNCTION__, $function))) {
            return $has; // Already cached this.
        }
        if (is_null($disabled_functions = &$this->staticKey(__FUNCTION__.'_disabled_functions'))) {
            $disabled_functions = array(); // Initialize disabled/blacklisted functions.

            if (($disable_functions = $this->Utils->Trim(ini_get('disable_functions')))) {
                $disabled_functions = array_merge($disabled_functions, preg_split('/[\s;,]+/u', mb_strtolower($disable_functions), -1, PREG_SPLIT_NO_EMPTY));
            }
            if (($blacklist_functions = $this->Utils->Trim(ini_get('suhosin.executor.func.blacklist')))) {
                $disabled_functions = array_merge($disabled_functions, preg_split('/[\s;,]+/u', mb_strtolower($blacklist_functions), -1, PREG_SPLIT_NO_EMPTY));
            }
        }
        if (!function_exists($function) || !is_callable($function)) {
            return ($has = false); // Not possible.
        }
        if ($disabled_functions && in_array(mb_strtolower($function), $disabled_functions, true)) {
            return ($has = false); // Not possible.
        }
        return ($has = true);
    }
}
