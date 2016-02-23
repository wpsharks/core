<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Clip utilities.
 *
 * @since 150424 Initial release.
 */
class Clip extends Classes\Core
{
    /**
     * Clips string(s) to X chars deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value          Any input value.
     * @param int   $max_length     Defaults to `80`.
     * @param bool  $force_ellipsis Defaults to `false`.
     *
     * @return string|array|object Clipped value.
     */
    public function __invoke($value, int $max_length = 80, bool $force_ellipsis = false)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($_value, $max_length, $force_ellipsis);
            } // unset($_key, $_value); // Housekeeping.
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Empty.
        }
        $max_length = max(6, $max_length);
        $string     = c\html_to_text($string, ['br2nl' => false]);

        if (mb_strlen($string) > $max_length) {
            $string = (string) mb_substr($string, 0, $max_length - 5).'[...]';
        } elseif ($force_ellipsis && mb_strlen($string) + 5 > $max_length) {
            $string = (string) mb_substr($string, 0, $max_length - 5).'[...]';
        } else {
            $string .= $force_ellipsis ? '[...]' : '';
        }
        return $string;
    }
}
