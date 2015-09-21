<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * Coalesce utilities.
 *
 * @since 150424 Initial release.
 */
class Coalesce extends AbsBase
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
     * Utility; `!empty()` coalesce.
     *
     * @since 15xxxx Initial release.
     *
     * @param mixed ...$vars Input vars.
     *
     * @return mixed First `!empty()`; else `NULL`.
     */
    public function notEmpty(...$vars)
    {
        foreach ($vars as $_var) {
            if (!empty($_var)) {
                return $_var;
            }
        }
        return;
    }

    /**
     * Utility; `!empty()` coalesce.
     *
     * @since 15xxxx Initial release.
     *
     * @param mixed &...$vars Input vars.
     *
     * @return mixed First `!empty()`; else `NULL`.
     *
     * @note This variation supports variables by reference; avoiding `E_NOTICE` errors.
     */
    public function notEmptyByRef(&...$vars)
    {
        foreach (func_get_args() as $_var) {
            if (!empty($_var)) {
                return $_var;
            }
        }
        return;
    }

    /**
     * Utility; `isset()` coalesce.
     *
     * @since 15xxxx Initial release.
     *
     * @param mixed ...$vars Input vars.
     *
     * @return mixed First `isset()`; else `NULL`.
     */
    public function notNull(...$vars)
    {
        foreach ($vars as $_var) {
            if (isset($_var)) {
                return $_var;
            }
        }
        return;
    }

    /**
     * Utility; `isset()` coalesce.
     *
     * @since 15xxxx Initial release.
     *
     * @param mixed ...$vars Input vars.
     *
     * @return mixed First `isset()`; else `NULL`.
     *
     * @note This variation supports variables by reference; avoiding `E_NOTICE` errors.
     */
    public function notNullByRef(&$a, &$b = null, &$c = null, &$d = null, &$e = null, &$f = null, &$g = null, &$h = null, &$i = null, &$j = null)
    {
        foreach (func_get_args() as $_var) {
            if (isset($_var)) {
                return $_var;
            }
        }
        return;
    }
}
