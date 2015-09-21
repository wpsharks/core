<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * Replace once utilities.
 *
 * @since 150424 Initial release.
 */
class ReplaceOnce extends AbsBase
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
    public function __invoke($needle, $replace, $value, $caSe_insensitive = false)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($needle, $replace, $_value, $caSe_insensitive);
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
    public function i($needle, $replace, $value)
    {
        return $this->__invoke($needle, $replace, $value, true);
    }
}
