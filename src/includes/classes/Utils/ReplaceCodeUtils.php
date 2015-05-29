<?php
namespace WebSharks\Core\Classes\Utils;

/**
 * Replace utilities.
 *
 * @since 150424 Initial release.
 */
class ReplaceCodeUtils extends AbsBase
{
    abstract public function arrayDotKeys(array $array);
    abstract public function arrayDimensionOne(array $array);
    abstract public function varDump($var, $echo = false, $indent_size = 4, $indent_char = ' ', $dump_circular_ids = false);
    abstract public function urlQueryBuild(array $args, $numeric_prefix = null, $arg_separator = '&', $enc_type = self::RFC1738, $___nested_key = null);
    abstract public function wildcardPatternIn($wildcard, $value, $caSe_insensitive = false, $collect_key_props = false, $x_flags = null);

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
     * Process replacement codes deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed  $value               Any value will do just fine here.
     * @param array  $vars                Vars that will be used to fill replacement codes.
     * @param bool   $urlencode           Optional. Defaults to a `FALSE` value. If this is `TRUE`, all replacement
     *                                    code values will be urlencoded automatically. Setting this to a `TRUE` value
     *                                    also enables some additional magic replacement codes.
     * @param string $implode_non_scalars Optional. By default, any non-scalar values in `$meta_vars` and/or `$vars`
     *                                    will be JSON encoded by this routine before replacements are performed.
     *                                    However, this behavior can be modified by passing this parameter
     *                                    with a non-empty string value to implode such values by.
     * @param bool   $caSe_insensitive    CaSe insensitive? Defaults to a `FALSE` value.
     * @param bool   $___raw_vars         Internal use only.
     * @param bool   $___vars             Internal use only.
     * @param bool   $___recursion        Internal use only.
     *
     * @return string|array|object Value after replacing all codes deeply.
     */
    public function replaceCodes(
        $value,
        array $vars,
        $urlencode = false,
        $implode_non_scalars = '',
        $caSe_insensitive = false,
        $___raw_vars = null,
        $___vars = null,
        $___recursion = null
    ) {
        $implode_non_scalars = (string) $implode_non_scalars;

        if (isset($___raw_vars, $___vars)) {
            goto replace_codes_deep;
        }
        $___raw_vars = $vars; // Copy of input vars.
        $___vars     = array(); // Initialize.

        foreach ($this->arrayDotKeys($___raw_vars) as $_key => $_value) {
            if (is_resource($_value)) {
                continue;
            }
            if (!is_scalar($_value)) {
                if ($implode_non_scalars) {
                    $_value = (array) $_value;
                    $_value = $this->arrayDimensionOne($_value);
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
                $_value = $this->replaceCodes(
                    $_value,
                    $vars,
                    $urlencode,
                    $implode_non_scalars,
                    $caSe_insensitive,
                    $___raw_vars,
                    $___vars,
                    true
                );
            }
            unset($_key, $_value); // Housekeeping.

            if (!$___recursion) {
                return $this->replaceCodes(
                    $value,
                    $vars,
                    $urlencode,
                    $implode_non_scalars,
                    $caSe_insensitive,
                    $___raw_vars,
                    $___vars,
                    true
                );
            }
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
            $value = $str_replace_('%%__var_dump__%%', $urlencode_($this->varDump($___vars)), $value);
        }
        if (stripos($value, '%%__serialize__%%') !== false) {
            $value = $str_replace_('%%__serialize__%%', $urlencode_(serialize($___vars)), $value);
        }
        if (stripos($value, '%%__json_encode__%%') !== false) {
            $value = $str_replace_('%%__json_encode__%%', $urlencode_(json_encode($___vars)), $value);
        }
        if ($urlencode && stripos($value, '%%__query_string__%%') !== false) {
            $value = $str_replace_('%%__query_string__%%', $this->urlQueryBuild($___vars), $value);
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
                        return $this->urlQueryBuild($values);
                    }
                    return $urlencode_(implode($m['delimiter'], $values));
                },
                $value
            );
            unset($_this); // Housekeeping.
        }
        if (!$___recursion) {
            return $this->replaceCodes(
                $value,
                $vars,
                $urlencode,
                $implode_non_scalars,
                $caSe_insensitive,
                $___raw_vars,
                $___vars,
                true
            );
        }
        return preg_replace('/%%.+?%%/', '', $value);
    }

    /**
     * Process replacement codes deeply (caSe insensitive).
     *
     * @since 150424 Initial release.
     *
     * @param mixed  $value               Any value will do just fine here.
     * @param array  $vars                Vars that will be used to fill replacement codes.
     * @param bool   $urlencode           Optional. Defaults to a `FALSE` value. If this is `TRUE`, all replacement
     *                                    code values will be urlencoded automatically. Setting this to a `TRUE` value
     *                                    also enables some additional magic replacement codes.
     * @param string $implode_non_scalars Optional. By default, any non-scalar values in `$meta_vars` and/or `$vars`
     *                                    will be JSON encoded by this routine before replacements are performed.
     *                                    However, this behavior can be modified by passing this parameter
     *                                    with a non-empty string value to implode such values by.
     *
     * @return string|array|object Value after replacing all codes deeply.
     */
    public function replaceCodesI($value, array $vars = array(), $urlencode = false, $implode_non_scalars = '')
    {
        return $this->replaceCodes($value, $vars, $urlencode, $implode_non_scalars, true);
    }
}
