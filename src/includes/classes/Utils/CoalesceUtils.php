<?php
namespace WebSharks\Core\Classes\Utils;

/**
 * Coalesce utilities.
 *
 * @since 150424 Initial release.
 */
class CoalesceUtils extends AbsBase
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
     * @return mixed First `!empty()`; else `NULL`.
     */
    public function coalesce()
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
    public function coalesceByRef(&$a, &$b = null, &$c = null, &$d = null, &$e = null, &$f = null, &$g = null, &$h = null, &$i = null, &$j = null)
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
    public function coalesceIsset()
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
    public function coalesceIssetByRef(&$a, &$b = null, &$c = null, &$d = null, &$e = null, &$f = null, &$g = null, &$h = null, &$i = null, &$j = null)
    {
        foreach (func_get_args() as $var) {
            if (isset($var)) {
                return $var;
            }
        }
        return;
    }
}
