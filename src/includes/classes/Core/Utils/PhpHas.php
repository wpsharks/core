<?php
/**
 * Php has utilities.
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
 * Php has utilities.
 *
 * @since 150424 Initial release.
 */
class PhpHas extends Classes\Core\Base\Core
{
    /**
     * Global static cache.
     *
     * @since 160712 PhpHas utilities.
     *
     * @var array Global static cache.
     */
    protected static $cache;

    /**
     * PHP's language constructs.
     *
     * @since 160219 Adding constructs.
     *
     * @var array Constructs.
     */
    const CONSTRUCTS = [
        'die',
        'echo',
        'empty',
        'exit',
        'eval',
        'include',
        'include_once',
        'isset',
        'list',
        'require',
        'require_once',
        'return',
        'print',
        'unset',
        '__halt_compiler',
    ];

    /**
     * Is function callable?
     *
     * @since 150424 Initial release.
     *
     * @param string $function A PHP function.
     *
     * @return bool True if the function is callable.
     *
     * @note This checks (among other things) if the function exists and that it's callable.
     *    It also checks INI `disable_functions` & `suhosin.executor.func.blacklist`.
     *    In addition, it also checks `suhosin.executor.disable_eval`.
     */
    public function callableFunc(string $function): bool
    {
        if (isset(static::$cache[__FUNCTION__][$function = mb_strtolower($function)])) {
            return static::$cache[__FUNCTION__][$function]; // Already cached this.
        }
        if (!isset(static::$cache[__FUNCTION__.'s_disabled'])) {
            static::$cache[__FUNCTION__.'s_disabled'] = []; // Initialize.
            $_functions_disabled                      = &static::$cache[__FUNCTION__.'s_disabled'];

            if (($_ini_disable_functions = (string) ini_get('disable_functions'))) {
                $_ini_disable_functions = mb_strtolower($_ini_disable_functions);
                $_functions_disabled    = array_merge($_functions_disabled, preg_split('/[\s;,]+/u', $_ini_disable_functions, -1, PREG_SPLIT_NO_EMPTY));
            }
            if (($_ini_suhosin_blacklist_functions = (string) ini_get('suhosin.executor.func.blacklist'))) {
                $_ini_suhosin_blacklist_functions = mb_strtolower($_ini_suhosin_blacklist_functions);
                $_functions_disabled              = array_merge($_functions_disabled, preg_split('/[\s;,]+/u', $_ini_suhosin_blacklist_functions, -1, PREG_SPLIT_NO_EMPTY));
            }
            if (filter_var(@ini_get('suhosin.executor.disable_eval'), FILTER_VALIDATE_BOOLEAN)) {
                $_functions_disabled[] = 'eval'; // The `eval()` construct is disabled also.
            }
        } // We now have a full list of all disabled functions.

        static::$cache[__FUNCTION__][$function] = null; // Initialize.
        $functions_disabled                     = &static::$cache[__FUNCTION__.'s_disabled'];
        $has                                    = &static::$cache[__FUNCTION__][$function];

        if ($functions_disabled && in_array($function, $functions_disabled, true)) {
            return $has = false; // Not possible; e.g., `eval()`.
        } elseif ((!function_exists($function) || !is_callable($function)) && !in_array($function, $this::CONSTRUCTS, true)) {
            return $has = false; // Not possible.
        }
        return $has = true;
    }
}
