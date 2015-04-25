<?php
namespace WebSharks\Core\Traits;

/**
 * Coalesce.
 *
 * @since 150424 Initial release.
 */
trait Coalesce
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
        return; // Default value.
    }
}
