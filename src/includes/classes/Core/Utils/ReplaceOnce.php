<?php
/**
 * Replace once utilities.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Replace once utilities.
 *
 * @since 150424 Initial release.
 */
class ReplaceOnce extends Classes\Core\Base\Core
{
    /**
     * String replace (ONE time) deeply.
     *
     * @since 150424 Initial release.
     *
     * @param string|array $needle           String, or an array of strings, to search for.
     * @param string|array $replace          String, or an array of strings, to use as replacements.
     * @param mixed        $value            Any input value will do just fine here.
     * @param bool         $caSe_insensitive Case insensitive? Defaults to FALSE.
     *
     * @return string|array|object Values after ONE string replacement deeply.
     */
    public function __invoke($needle, $replace, $value, bool $caSe_insensitive = false)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($needle, $replace, $_value, $caSe_insensitive);
            } // unset($_key, $_value); // Housekeeping.
            return $value;
        }
        $value     = (string) $value; // Force string (always).
        $mb_strpos = $caSe_insensitive ? 'mb_stripos' : 'mb_strpos';

        if (is_array($needle) || is_object($needle)) {
            $needle = (array) $needle; // Force array.

            if (is_array($replace) || is_object($replace)) {
                $replace = (array) $replace; // Force array.

                foreach ($needle as $_key => $_needle) {
                    $_needle = (string) $_needle;
                    if (($_mb_strpos = $mb_strpos($value, $_needle)) !== false) {
                        $_mb_strlen = mb_strlen($_needle);
                        $_replace   = isset($replace[$_key]) ? (string) $replace[$_key] : '';
                        $value      = $this->c::mbSubstrReplace($value, $_replace, $_mb_strpos, $_mb_strlen);
                    }
                } // unset($_key, $_needle, $_mb_strpos, $_mb_strlen, $_replace);

                return $value;
            } else {
                $replace = (string) $replace; // Force string.

                foreach ($needle as $_key => $_needle) {
                    $_needle = (string) $_needle;
                    if (($_mb_strpos = $mb_strpos($value, $_needle)) !== false) {
                        $_mb_strlen = mb_strlen($_needle);
                        $value      = $this->c::mbSubstrReplace($value, $replace, $_mb_strpos, $_mb_strlen);
                    }
                } // unset($_key, $_needle, $_mb_strpos, $_mb_strlen);

                return $value;
            }
        } else {
            $needle = (string) $needle; // Force string.

            if (($_mb_strpos = $mb_strpos($value, $needle)) !== false) {
                $_mb_strlen = mb_strlen($needle);

                if (is_array($replace) || is_object($replace)) {
                    $replace  = array_values((array) $replace); // Force array.
                    $_replace = isset($replace[0]) ? (string) $replace[0] : '';
                } else {
                    $_replace = (string) $replace; // Force string.
                }
                $value = $this->c::mbSubstrReplace($value, $_replace, $_mb_strpos, $_mb_strlen);
                // unset($_mb_strpos, $_mb_strlen, $_replace);
            }
            return $value;
        }
    }

    /**
     * CaSe insensitive string replace (ONE time) deeply (caSe insensitive).
     *
     * @since 150424 Initial release.
     *
     * @param string|array $needle  String, or an array of strings to search for.
     * @param string|array $replace String, or an array of strings to use as replacements.
     * @param mixed        $value   Any input value will do just fine here.
     *
     * @return string|array|object Values after ONE string replacement deeply.
     */
    public function i($needle, $replace, $value)
    {
        return $this->__invoke($needle, $replace, $value, true);
    }
}
