<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * Replace utilities.
 *
 * @since 150424 Initial release.
 */
class ReplaceCodes extends AbsBase
{
    protected $WdRegex;
    protected $VarDump;
    protected $UrlQuery;
    protected $RegexQuote;
    protected $RegexPattern;
    protected $ArrayDotKeys;
    protected $ArrayDimensions;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(
        WdRegex $WdRegex,
        VarDump $VarDump,
        UrlQuery $UrlQuery,
        RegexQuote $RegexQuote,
        RegexPattern $RegexPattern,
        ArrayDotKeys $ArrayDotKeys,
        ArrayDimensions $ArrayDimensions
    ) {
        parent::__construct();

        $this->WdRegex         = $WdRegex;
        $this->VarDump         = $VarDump;
        $this->UrlQuery        = $UrlQuery;
        $this->RegexQuote      = $RegexQuote;
        $this->RegexPattern    = $RegexPattern;
        $this->ArrayDotKeys    = $ArrayDotKeys;
        $this->ArrayDimensions = $ArrayDimensions;
    }

    /**
     * Fill replacement codes deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed  $value               Any value will do just fine here.
     * @param array  $vars                Vars that will be used to fill replacement codes.
     *                                    Variable keys may NOT contain these characters: `%`, `/`
     * @param bool   $urlencode           Optional. If this is `true`, all replacement code values will be urlencoded automatically.
     * @param string $implode_non_scalars Optional. By default, any non-scalar values will be JSON-encoded by this routine, before replacements occur.
     *                                    However, this behavior can be modified by passing this parameter with a non-empty string value to implode such values by.
     * @param bool   $___raw_vars         Internal use only; during recursion.
     * @param bool   $___vars             Internal use only; during recursion.
     *
     * @return string|array|object After replacing all codes deeply.
     */
    public function __invoke(
        $value,
        array $vars,
        bool $urlencode = false,
        string $implode_non_scalars = '',
        bool $___raw_vars = null,
        bool $___vars = null
    ) {
        if (isset($___raw_vars, $___vars)) {
            goto replace_codes_deep;
        }
        $___raw_vars = $vars; // Copy.
        $___vars     = []; // Initialize.

        foreach ($this->ArrayDotKeys->get($___raw_vars) as $_key => $_value) {
            if (is_resource($_value)) {
                continue; // Not possible.
            }
            if (!is_scalar($_value)) {
                if ($implode_non_scalars) {
                    $_value = (array) $_value;
                    $_value = $this->ArrayDimensions->one($_value);
                    $_value = implode($implode_non_scalars, $_value);
                } else {
                    $_value = json_encode($_value);
                }
            } elseif ($_value === false) {
                $_value = '0';
            }
            $___vars[$_key] = (string) $_value;
        } // unset($_key, $_value); // Housekeeping.

        replace_codes_deep: // Replacements.

        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke(
                    $_value,
                    $vars,
                    $urlencode,
                    $implode_non_scalars,
                    $___raw_vars,
                    $___vars
                );
            } // unset($_key, $_value);

            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        if (mb_strpos($string, '%%') === false) {
            return $string; // Nothing to do.
        }
        $maybe_urlencode = $urlencode ? 'urlencode' : function ($v) {
            return $v; // Do nothing; passthrough.
        };
        if (mb_stripos($string, '%%__var_dump__%%') !== false) {
            $string = preg_replace_callback('/%%__var_dump__%%/ui', function () use (&$maybe_urlencode, &$___vars) {
                return $maybe_urlencode($this->VarDump($___vars));
            }, $string);
        }
        if (mb_stripos($string, '%%__serialize__%%') !== false) {
            $string = preg_replace_callback('/%%__serialize__%%/ui', function () use (&$maybe_urlencode, &$___vars) {
                return $maybe_urlencode(serialize($___vars));
            }, $string);
        }
        if (mb_stripos($string, '%%__json_encode__%%') !== false) {
            $string = preg_replace_callback('/%%__json_encode__%%/ui', function () use (&$maybe_urlencode, &$___vars) {
                return $maybe_urlencode(json_encode($___vars));
            }, $string);
        }
        if ($urlencode && mb_stripos($string, '%%__query_string__%%') !== false) {
            $string = preg_replace_callback('/%%__query_string__%%/ui', function () use (&$___vars) {
                return $this->UrlQuery->build($___vars);
            }, $string);
        }
        $_iteration_counter = 0; // Check completion every 5th iteration to save time.

        foreach ($___vars as $_key => $_value) {
            $string = preg_replace_callback('/%%'.$this->RegexQuote($_key).'%%/ui', function () use (&$maybe_urlencode, &$_value) {
                return $maybe_urlencode($_value); // Fill replacement codes.
            }, $string); // Note that these are all caSe-insensitive.

            if (++$_iteration_counter >= 5) {
                $_iteration_counter = 0;
                if (mb_strpos($string, '%%') === false) {
                    return $string;
                }
            }
        } // unset($_iteration_counter, $_key, $_value); // Housekeeping.

        if (mb_strpos($string, '%%/') !== false) { // Watered-down regex-based codes?
            $___var_keys = array_keys($___vars); // One-time only; may need these down below.

            $string = preg_replace_callback(
                '/%%\/(?P<pattern>[^%\/]+?)(?:\/(?P<delimiter>[^%\/]*?))?(?:\/(?P<key_delimiter>[^%\/]*?))?%%/u',
                function ($m) use (&$maybe_urlencode, &$___vars, &$___var_keys) {
                    $values = []; // Initialize.
                    $regex = $this->WdRegex($m['pattern'], '.');

                    if (!($keys = $this->RegexPattern->in($regex, $___var_keys, true))) {
                        return; // No matching keys.
                    }
                    foreach ($keys as $_key) { // Matching keys.
                        $values[$___var_keys[$_key]] = $___vars[$___var_keys[$_key]];
                    } // unset($_key); // Housekeeping.

                    if (empty($m['delimiter'])) {
                        $m['delimiter'] = ', ';
                    }
                    if (empty($m['key_delimiter'])) {
                        $m['key_delimiter'] = '';
                    }
                    $m['delimiter'] = str_replace(
                        array('\r', '\n', '\t'),
                        array("\r", "\n", "\t"),
                        $m['delimiter']
                    );
                    $m['key_delimiter'] = str_replace(
                        array('\r', '\n', '\t'),
                        array("\r", "\n", "\t"),
                        $m['key_delimiter']
                    );
                    if ($m['delimiter'] === '&' && $m['key_delimiter'] === '=') {
                        return $this->UrlQuery->build($values);
                    }
                    if ($m['key_delimiter']) {
                        foreach ($values as $_key => &$_value) {
                            $_value = $_key.$m['key_delimiter'].$_value;
                        } // unset($_key, $_value); // Housekeeping.
                    }
                    return $maybe_urlencode(implode($m['delimiter'], $values));
                },
                $string
            );
        }
        return preg_replace('/%%.+?%%/us', '', $string);
    }
}
