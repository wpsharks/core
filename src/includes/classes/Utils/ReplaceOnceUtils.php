<?php
namespace WebSharks\Core\Traits;

/**
 * Replace once utilities.
 *
 * @since 150424 Initial release.
 */
trait ReplaceOnceUtils
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
    protected function replaceOnce($needle, $replace, $value, $caSe_insensitive = false)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->replaceOnce($needle, $replace, $_value, $caSe_insensitive);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        $value  = (string) $value;
        $strpos = $caSe_insensitive ? 'stripos' : 'strpos';

        if (is_array($needle)) {
            if (is_array($replace)) {
                foreach ($needle as $_key => $_needle) {
                    $_needle = (string) $_needle;
                    if (($_strpos = $strpos($value, $_needle)) !== false) {
                        $_length  = strlen($_needle);
                        $_replace = isset($replace[$_key]) ? (string) $replace[$_key] : '';
                        $value    = substr_replace($value, $_replace, $_strpos, $_length);
                    }
                }
                unset($_key, $_needle, $_strpos, $_length, $_replace);

                return $value;
            } else {
                $replace = (string) $replace;
                foreach ($needle as $_key => $_needle) {
                    $_needle = (string) $_needle;
                    if (($_strpos = $strpos($value, $_needle)) !== false) {
                        $_length = strlen($_needle);
                        $value   = substr_replace($value, $replace, $_strpos, $_length);
                    }
                }
                unset($_key, $_needle, $_strpos, $_length);

                return $value;
            }
        } else {
            $needle = (string) $needle;
            if (($_strpos = $strpos($value, $needle)) !== false) {
                $_length = strlen($needle);
                if (is_array($replace)) {
                    $_replace = isset($replace[0]) ? (string) $replace[0] : '';
                } else {
                    $_replace = (string) $replace;
                }
                $value = substr_replace($value, $_replace, $_strpos, $_length);

                unset($_strpos, $_length, $_replace);
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
    protected function replaceOnceI($needle, $replace, $value)
    {
        return $this->replaceOnce($needle, $replace, $value, true);
    }
}
