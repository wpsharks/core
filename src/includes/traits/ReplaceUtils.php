<?php
namespace WebSharks\Core\Traits;

/**
 * Replace Utilities.
 *
 * @since 150424 Initial release.
 */
trait ReplaceUtils
{
    use DimensionUtils;
    use DotKeyUtils;
    use DumpUtils;
    use QueryUtils;
    use WildcardUtils;

    /**
     * String replace (ONE time).
     *
     * @param string|array $needle           String, or an array of strings to search for.
     * @param string|array $replace          String, or an array of strings to use as replacements.
     * @param string       $string           A string to run replacements on (i.e., the string to search in).
     * @param bool         $caSe_insensitive Case insensitive? Defaults to FALSE.
     *
     * @return string Value of `$string` after ONE string replacement.
     */
    protected function replaceOnce($needle, $replace, $string, $caSe_insensitive = false)
    {
        return $this->replaceOnceDeep($needle, $replace, (string) $string, $caSe_insensitive);
    }

    /**
     * CaSe insensitive string replace (ONE time).
     *
     * @param string|array $needle  String, or an array of strings to search for.
     * @param string|array $replace String, or an array of strings to use as replacements.
     * @param string       $string  A string to run replacements on (i.e., the string to search in).
     *
     * @return string Value of `$string` after ONE replacement.
     */
    protected function ireplaceOnce($needle, $replace, $string)
    {
        return $this->replaceOnceDeep($needle, $replace, (string) $string, true);
    }

    /**
     * CaSe insensitive string replace (ONE time); deeply.
     *
     * @param string|array $needle  String, or an array of strings to search for.
     * @param string|array $replace String, or an array of strings to use as replacements.
     * @param mixed        $value   Any value can be converted into a string to run replacements on.
     *                              Actually, objects can't, but this recurses into objects.
     *
     * @return mixed Values after ONE replacement (deeply).
     *               Any values that were NOT strings|arrays|objects will be
     *               converted to strings by this routine.
     */
    protected function ireplaceOnceDeep($needle, $replace, $value)
    {
        return $this->replaceOnceDeep($needle, $replace, $value, true);
    }

    /**
     * String replace (ONE time); deeply.
     *
     * @param string|array $needle           String, or an array of strings, to search for.
     * @param string|array $replace          String, or an array of strings, to use as replacements.
     * @param mixed        $value            Any value can be converted into a string to run replacements on.
     *                                       Actually, objects can't, but this recurses into objects.
     * @param bool         $caSe_insensitive Case insensitive? Defaults to FALSE.
     *
     * @return mixed Values after ONE string replacement (deeply).
     *               Any values that were NOT strings|arrays|objects, will be converted to strings by this routine.
     *
     * @see http://stackoverflow.com/questions/8177296/when-to-use-strtr-vs-str-replace
     */
    protected function replaceOnceDeep($needle, $replace, $value, $caSe_insensitive = false)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->replaceOnceDeep($needle, $replace, $_value, $caSe_insensitive);
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
     * Process replacement codes.
     *
     * @param mixed  $string              String to replace codes in.
     * @param array  $meta_vars           Optional. Defaults to an empty array.
     *                                    This array is always given precedence over any other secondary `$vars`.
     *                                    This is the primary array of data which will be used to replace codes.
     *                                    Normally an associative array, but a numerically indexed array is fine too.
     * @param array  $vars                Optional (any other secondary vars). Defaults to an empty array.
     *                                    This is an additional array of data which will be used to replace codes.
     *                                    Normally an associative array, but a numerically indexed array is fine too.
     * @param bool   $caSe_insensitive    CaSe insensitive? Defaults to a `FALSE` value (caSe sensitivity on).
     * @param bool   $urlencode           Optional. Defaults to a `FALSE` value. If this is `TRUE`, all replacement
     *                                    code values will be urlencoded automatically. Setting this to a `TRUE` value
     *                                    also enables some additional magic replacement codes.
     * @param string $implode_non_scalars Optional. By default, any non-scalar values in `$meta_vars` and/or `$vars`
     *                                    will be JSON encoded by this routine before replacements are performed.
     *                                    However, this behavior can be modified by passing this parameter
     *                                    with a non-empty string value to implode such values by.
     *
     * @return string String after replacing all codes.
     */
    protected function replaceCodes($string, array $meta_vars = array(), array $vars = array(), $caSe_insensitive = false, $urlencode = false, $implode_non_scalars = '')
    {
        return $this->replaceCodesDeep((string) $string, $meta_vars, $vars, $caSe_insensitive, false, $urlencode, $implode_non_scalars);
    }

    /**
     * Process replacement codes (caSe insensitive).
     *
     * @param mixed  $string              String to replace codes in.
     * @param array  $meta_vars           Optional. Defaults to an empty array.
     *                                    This array is always given precedence over any other secondary `$vars`.
     *                                    This is the primary array of data which will be used to replace codes.
     *                                    Normally an associative array, but a numerically indexed array is fine too.
     * @param array  $vars                Optional (any other secondary vars). Defaults to an empty array.
     *                                    This is an additional array of data which will be used to replace codes.
     *                                    Normally an associative array, but a numerically indexed array is fine too.
     * @param bool   $urlencode           Optional. Defaults to a `FALSE` value. If this is `TRUE`, all replacement
     *                                    code values will be urlencoded automatically. Setting this to a `TRUE` value
     *                                    also enables some additional magic replacement codes.
     * @param string $implode_non_scalars Optional. By default, any non-scalar values in `$meta_vars` and/or `$vars`
     *                                    will be JSON encoded by this routine before replacements are performed.
     *                                    However, this behavior can be modified by passing this parameter
     *                                    with a non-empty string value to implode such values by.
     *
     * @return string String after replacing all codes.
     */
    protected function ireplaceCodes($string, array $meta_vars = array(), array $vars = array(), $urlencode = false, $implode_non_scalars = '')
    {
        return $this->replaceCodesDeep((string) $string, $meta_vars, $vars, true, false, $urlencode, $implode_non_scalars);
    }

    /**
     * Process replacement codes deeply (caSe insensitive).
     *
     * @param mixed  $value               Any value will do just fine here.
     * @param array  $meta_vars           Optional. Defaults to an empty array.
     *                                    This array is always given precedence over any other secondary `$vars`.
     *                                    This is the primary array of data which will be used to replace codes.
     *                                    Normally an associative array, but a numerically indexed array is fine too.
     * @param array  $vars                Optional (any other secondary vars). Defaults to an empty array.
     *                                    This is an additional array of data which will be used to replace codes.
     *                                    Normally an associative array, but a numerically indexed array is fine too.
     * @param bool   $preserve_types      Optional. Defaults to a `FALSE` value. If this is `TRUE`, we will preserve
     *                                    data types; only searching/replacing existing string values deeply.
     *                                    By default, anything that is NOT an array/object is converted
     *                                    to a string by this routine.
     * @param bool   $urlencode           Optional. Defaults to a `FALSE` value. If this is `TRUE`, all replacement
     *                                    code values will be urlencoded automatically. Setting this to a `TRUE` value
     *                                    also enables some additional magic replacement codes.
     * @param string $implode_non_scalars Optional. By default, any non-scalar values in `$meta_vars` and/or `$vars`
     *                                    will be JSON encoded by this routine before replacements are performed.
     *                                    However, this behavior can be modified by passing this parameter
     *                                    with a non-empty string value to implode such values by.
     *
     * @return mixed Values after replacing all codes (deeply). By default, any values that were
     *               NOT strings|arrays|objects, will be converted to strings by this routine.
     *               Pass `$preserve_types` as `TRUE` to prevent this from occurring.
     */
    protected function ireplaceCodesDeep($value, array $meta_vars = array(), array $vars = array(), $preserve_types = false, $urlencode = false, $implode_non_scalars = '')
    {
        return $this->replaceCodesDeep($value, $meta_vars, $vars, true, $preserve_types, $urlencode, $implode_non_scalars);
    }

    /**
     * Process replacement codes deeply.
     *
     * @param mixed  $value               Any value will do just fine here.
     * @param array  $meta_vars           Optional. Defaults to an empty array.
     *                                    This array is always given precedence over any other secondary `$vars`.
     *                                    This is the primary array of data which will be used to replace codes.
     *                                    Normally an associative array, but a numerically indexed array is fine too.
     * @param array  $vars                Optional (any other secondary vars). Defaults to an empty array.
     *                                    This is an additional array of data which will be used to replace codes.
     *                                    Normally an associative array, but a numerically indexed array is fine too.
     * @param bool   $caSe_insensitive    CaSe insensitive? Defaults to a `FALSE` value (caSe sensitivity on).
     * @param bool   $preserve_types      Optional. Defaults to a `FALSE` value. If this is `TRUE`, we will preserve
     *                                    data types; only searching/replacing existing string values deeply.
     *                                    By default, anything that is NOT an array/object is converted
     *                                    to a string by this routine.
     * @param bool   $urlencode           Optional. Defaults to a `FALSE` value. If this is `TRUE`, all replacement
     *                                    code values will be urlencoded automatically. Setting this to a `TRUE` value
     *                                    also enables some additional magic replacement codes.
     * @param string $implode_non_scalars Optional. By default, any non-scalar values in `$meta_vars` and/or `$vars`
     *                                    will be JSON encoded by this routine before replacements are performed.
     *                                    However, this behavior can be modified by passing this parameter
     *                                    with a non-empty string value to implode such values by.
     * @param bool   $___raw_vars         Internal use only.
     * @param bool   $___vars             Internal use only.
     * @param bool   $___recursion        Internal use only.
     *
     * @return mixed Values after replacing all codes (deeply). By default, any values that were
     *               NOT strings|arrays|objects, will be converted to strings by this routine.
     *               Pass `$preserve_types` as `TRUE` to prevent this from occurring.
     */
    protected function replaceCodesDeep(
        $value,
        array $meta_vars = array(),
        array $vars = array(),
        $caSe_insensitive = false,
        $preserve_types = false,
        $urlencode = false,
        $implode_non_scalars = '',
        $___raw_vars = null,
        $___vars = null,
        $___recursion = null
    ) {
        $implode_non_scalars = (string) $implode_non_scalars;

        if (isset($___raw_vars, $___vars)) {
            goto replace_codes_deep;
        } // Did this already?

        $___vars     = array(); // Initialize.
        $___raw_vars = $meta_vars + $vars;

        foreach ($___raw_vars + $this->dotKeys($___raw_vars) as $_key => $_value) {
            if (is_resource($_value)) {
                continue;
            }
            if (!is_scalar($_value)) {
                if ($implode_non_scalars) {
                    $_value = (array) $_value;
                    $_value = $this->oneDimension($_value);
                    $_value = implode($implode_non_scalars, $_value);
                } else {
                    $_value = json_encode($_value);
                }
            } elseif ($_value === false) {
                $_value = '0';
            }
            $___vars[$_key] = (string) $_value;
        }
        unset($_key, $_value); // Housekeeping.

        replace_codes_deep: // Replacements.

        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->replaceCodesDeep(
                    $_value,
                    $meta_vars,
                    $vars,
                    $caSe_insensitive,
                    $preserve_types,
                    $urlencode,
                    $implode_non_scalars,
                    $___raw_vars,
                    $___vars,
                    true
                );
            }
            unset($_key, $_value); // Housekeeping.

            if (!$___recursion) {
                return $this->replaceCodesDeep(
                    $value,
                    $meta_vars,
                    $vars,
                    $caSe_insensitive,
                    $preserve_types,
                    $urlencode,
                    $implode_non_scalars,
                    $___raw_vars,
                    $___vars,
                    true
                );
            }
            return $value;
        }
        if ($preserve_types && !is_string($value)) {
            return $value;
        }
        if (!($value = (string) $value)) {
            return $value;
        }
        if (strpos($value, '%%') === false) {
            return $value;
        }
        $str_replace_ = $caSe_insensitive ? 'str_ireplace' : 'str_replace';
        $urlencode_   = $urlencode ? 'urlencode' : function ($v) {
            return $v; // Callback placeholder.
        };
        if (stripos($value, '%%__var_dump__%%') !== false) {
            $value = $str_replace_('%%__var_dump__%%', $urlencode_($this->dump($___raw_vars)), $value);
        }
        if (stripos($value, '%%__serialize__%%') !== false) {
            $value = $str_replace_('%%__serialize__%%', $urlencode_(serialize($___raw_vars)), $value);
        }
        if (stripos($value, '%%__json_encode__%%') !== false) {
            $value = $str_replace_('%%__json_encode__%%', $urlencode_(json_encode($___raw_vars)), $value);
        }
        if ($urlencode && stripos($value, '%%__query_string__%%') !== false) {
            $value = $str_replace_('%%__query_string__%%', $this->queryBuild($___vars), $value);
        }
        $_iteration_counter = 1; // Check completion every 10th iteration to save time.

        foreach ($___vars as $_key => $_value) {
            $value = $str_replace_('%%'.$_key.'%%', $urlencode_($_value), $value);
            if ($_iteration_counter >= 10) {
                $_iteration_counter = 0;
                if (strpos($value, '%%') === false) {
                    return $value;
                }
            }
            $_iteration_counter++; // Increment counter.
        }
        unset($_iteration_counter, $_key, $_value); // Housekeeping.

        if (strpos($value, '.*') !== false && strpos($value, '%%') !== false) {
            $value = preg_replace_callback(
                '/%%(?P<pattern>.+?\.\*)(?:\|(?P<delimiter>.*?))?'.
                '(?P<include_keys>\[(?P<key_delimiter>.*?)\])?%%/s',
                function ($m) use ($caSe_insensitive, $___vars, $urlencode_) {
                    $values      = array();
                    $___var_keys = array_keys($___vars);
                    $keys        = $this->wildcardPatternIn(
                        $m['pattern'],
                        $___var_keys,
                        $caSe_insensitive,
                        true
                    );
                    foreach ($keys as $_key) {
                        $values[$___var_keys[$_key]] = $___vars[$___var_keys[$_key]];
                    }
                    unset($_key); // Housekeeping.

                    if (empty($m['delimiter'])) {
                        $m['delimiter'] = ', ';
                    }
                    if (empty($m['include_keys'])) {
                        $m['include_keys'] = '';
                    }
                    if (empty($m['key_delimiter'])) {
                        $m['key_delimiter'] = ' = ';
                    }
                    $m['delimiter']     = str_replace(
                        array('\r', '\n', '\t'),
                        array("\r", "\n", "\t"),
                        $m['delimiter']
                    );
                    $m['key_delimiter'] = str_replace(
                        array('\r', '\n', '\t'),
                        array("\r", "\n", "\t"),
                        $m['key_delimiter']
                    );
                    if ($m['include_keys']) {
                        foreach ($values as $_key => &$_value) {
                            $_value = $_key.$m['key_delimiter'].$_value;
                        }
                        unset($_key, $_value); // Housekeeping.
                    }
                    if ($m['delimiter'] === '&' && !$m['include_keys']) {
                        return $this->queryBuild($values);
                    }
                    return $urlencode_(implode($m['delimiter'], $values));
                },
                $value
            );
            unset($_this); // Housekeeping.
        }
        if (!$___recursion) {
            return $this->replaceCodesDeep(
                $value,
                $meta_vars,
                $vars,
                $caSe_insensitive,
                $preserve_types,
                $urlencode,
                $implode_non_scalars,
                $___raw_vars,
                $___vars,
                true
            );
        }
        return preg_replace('/%%.+?%%/', '', $value);
    }
}
