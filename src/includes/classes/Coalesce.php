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
        foreach ($vars as $_var) {
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
     * @param mixed &...$vars Input vars.
     *
     * @return mixed First `isset()`; else `NULL`.
     *
     * @note This variation supports variables by reference; avoiding `E_NOTICE` errors.
     */
    public function notNullByRef(&...$vars)
    {
        foreach ($vars as $_var) {
            if (isset($_var)) {
                return $_var;
            }
        }
        return;
    }
}
