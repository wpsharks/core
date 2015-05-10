<?php
namespace WebSharks\Core\Traits;

/**
 * Coalesce utilities.
 *
 * @since 150424 Initial release.
 */
trait CoalesceUtils
{
    /**
     * Utility; `!empty()` coalesce.
     *
     * @since 15xxxx Initial release.
     *
     * @return mixed First `!empty()`; else `NULL`.
     */
    protected function coalesce()
    {
        foreach (func_get_args() as $var) {
            if (!empty($var)) {
                return $var;
            }
        }
        return;
    }

    /**
     * Utility; `!empty()` coalesce.
     *
     * @since 15xxxx Initial release.
     *
     * @return mixed First `!empty()`; else `NULL`.
     *
     * @note This variation supports variables by reference; avoiding `E_NOTICE` errors.
     */
    protected function coalesceByRef(&$a, &$b = null, &$c = null, &$d = null, &$e = null, &$f = null, &$g = null, &$h = null, &$i = null, &$j = null)
    {
        foreach (func_get_args() as $var) {
            if (!empty($var)) {
                return $var;
            }
        }
        return;
    }

    /**
     * Utility; `isset()` coalesce.
     *
     * @since 15xxxx Initial release.
     *
     * @return mixed First `isset()`; else `NULL`.
     */
    protected function coalesceIsset()
    {
        foreach (func_get_args() as $var) {
            if (isset($var)) {
                return $var;
            }
        }
        return;
    }

    /**
     * Utility; `isset()` coalesce.
     *
     * @since 15xxxx Initial release.
     *
     * @return mixed First `isset()`; else `NULL`.
     *
     * @note This variation supports variables by reference; avoiding `E_NOTICE` errors.
     */
    protected function coalesceIssetByRef(&$a, &$b = null, &$c = null, &$d = null, &$e = null, &$f = null, &$g = null, &$h = null, &$i = null, &$j = null)
    {
        foreach (func_get_args() as $var) {
            if (isset($var)) {
                return $var;
            }
        }
        return;
    }
}
