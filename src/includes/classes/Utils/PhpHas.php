<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * PhpHas utilities.
 *
 * @since 150424 Initial release.
 */
class PhpHas extends Classes\AbsBase
{
    /**
     * Is a particular function possible?
     *
     * @since 150424 Initial release.
     *
     * @param string $function A PHP function (or user function) to check.
     *
     * @return bool True if the function is possible.
     *
     * @note This checks (among other things) if the function exists and that it's callable.
     *    It also checks INI `disable_functions` & `suhosin.executor.func.blacklist`.
     */
    public function callableFunc(string $function): bool
    {
        if (!is_null($has = &$this->cacheKey(__FUNCTION__, $function))) {
            return $has; // Already cached this once before.
        }
        if (!function_exists($function) || !is_callable($function)) {
            return $has = false; // Not possible.
        }
        if (is_null($disabled_functions = &$this->cacheKey(__FUNCTION__.'_disabled_functions'))) {
            $disabled_functions = []; // Initialize disabled/blacklisted functions.

            if (($disable_functions = c\mb_trim(ini_get('disable_functions')))) {
                $disabled_functions = array_merge($disabled_functions, preg_split('/[\s;,]+/u', mb_strtolower($disable_functions), -1, PREG_SPLIT_NO_EMPTY));
            }
            if (($blacklist_functions = c\mb_trim(ini_get('suhosin.executor.func.blacklist')))) {
                $disabled_functions = array_merge($disabled_functions, preg_split('/[\s;,]+/u', mb_strtolower($blacklist_functions), -1, PREG_SPLIT_NO_EMPTY));
            }
        } // We now have a full list of all disabled functions in this environment.
        if ($disabled_functions && in_array(mb_strtolower($function), $disabled_functions, true)) {
            return $has = false; // Not possible.
        }
        return $has = true;
    }
}
