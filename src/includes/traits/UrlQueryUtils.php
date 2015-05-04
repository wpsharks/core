<?php
namespace WebSharks\Core\Traits;

/**
 * URL query utilities.
 *
 * @since 150424 Initial release.
 */
trait UrlQueryUtils
{
    abstract protected function &staticKey($function, $args = array());
    abstract protected function trim($value, $chars = '', $extra_chars = '', $side = '');
    abstract protected function urlParse($url_uri_qsl, $component = -1);
    abstract protected function urlParseUn(array $parts);

    /**
     * Acquire/sanitize query string.
     *
     * @since 150424 Initial release.
     *
     * @param string $qs_url_uri A query string (with or without a leading `?`), a URL, or URI.
     *
     * @return string Query string (no leading `?`); and w/ normalized ampersands.
     */
    protected function urlQueryString($qs_url_uri)
    {
        if (!($qs = (string) $qs_url_uri)) {
            return $qs; // Empty.
        }
        if (is_null($regex_amps = &$this->staticKey(__FUNCTION__.'_regex_amps'))) {
            $regex_amps = implode('|', array_keys($this->def_ampersand_entities));
        }
        $qs = preg_replace('/(?:'.$regex_amps.')/', '&', $qs);

        if (preg_match('/^'.$this->def_regex_frag_scheme.'/', $qs)) {
            $qs = (string) $this->urlParse($qs, PHP_URL_QUERY);
        } elseif (in_array($string[0], ['/', '?'], true)) {
            $qs = (string) $this->urlParse($qs, PHP_URL_QUERY);
        } elseif (strpos($qs, '?') !== false) {
            list(, $qs) = explode('?', $qs, 2);
        }
        $qs = $this->trim($qs, '', '?=&');

        return $qs;
    }

    /**
     * Generates an array from a string of query vars.
     *
     * @since 150424 Initial release.
     *
     * @param string      $qs_url_uri          A query string (with or without a leading `?`), a URL, or URI.
     * @param bool        $convert_dots_spaces Defaults to `TRUE` (just like PHP's {@link parse_str()} function).
     * @param null|string $dec_type            Optional. Defaults to {@link RFC1738} indicating `urldecode()`.
     *                                         Or, {@link RFC3986} indicating `rawurldecode()`.
     * @param null|array  $___parent_array     Internal use only; for recursion.
     *
     * @return array An array of query string args; based on the input `$qs_url_uri` value.
     */
    protected function urlQueryParse($qs_url_uri, $convert_dots_spaces = true, $dec_type = self::RFC1738, array &$___parent_array = null)
    {
        if (isset($___parent_array)) {
            $array = &$___parent_array;
            $qs    = (string) $qs_url_uri;
        } else {
            $array = array(); // Initialize.
            $qs    = $this->urlQueryString((string) $qs_url_uri);
        }
        foreach (explode('&', $qs) as $_name_value) {
            if (!isset($_name_value[0]) || $_name_value === '=') {
                continue; // Nothing to do.
            }
            $_name_value     = explode('=', $_name_value, 2);
            $_name           = $_name_value[0]; // Always has length.
            $_value          = isset($_name_value[1]) ? $_name_value[1] : '';

            if ($dec_type === $this::RFC1738) {
                $_name = urldecode($_name);
            } elseif ($dec_type === $this::RFC3986) {
                $_name = rawurldecode($_name);
            }
            if ($convert_dots_spaces) {
                $_name = str_replace(array('.', ' '), '_', $_name);
            }
            if (strpos($_name, '[') !== false // Quick check (optimization).
                && preg_match('/^(?P<name>[^\[]+)\[(?P<nested_name>[^\]]*)\](?P<deep>.*)$/', $_name, $_m)) {
                if (!isset($array[$_m['name']])) {
                    $array[$_m['name']] = array();
                }
                if (!isset($_m['nested_name'][0])) {
                    $_m['nested_name'] = count($array[$_m['name']]);
                }
                if ($dec_type === $this::RFC1738) {
                    $_value = urlencode($_m['nested_name'].$_m['deep']).'='.$_value;
                } elseif ($dec_type === $this::RFC3986) {
                    $_value = rawurlencode($_m['nested_name'].$_m['deep']).'='.$_value;
                } else {
                    $_value = $_m['nested_name'].$_m['deep'].'='.$_value;
                }
                $array[$_m['name']] = $this->urlQueryParse(
                    $_value,
                    $convert_dots_spaces,
                    $dec_type,
                    $array[$_m['name']]
                );
            } else {
                if ($dec_type === $this::RFC1738) {
                    $_value = urldecode($_value);
                } elseif ($dec_type === $this::RFC3986) {
                    $_value = rawurldecode($_value);
                }
                $array[$_name] = $_value;
            }
        }
        unset($_name_value, $_name, $_value, $_m);

        return $array; // Final array.
    }

    /**
     * Generates an array from a string of query vars.
     *
     * @since 150424 Initial release.
     *
     * @note This method is an alias for {@link urlQueryParse()} with `$dec_type` set to: {@link RFC3986}.
     *    Please check the {@link urlQueryParse()} method for further details.
     *
     * @param string $qs_url_uri          A query string (with or without a leading `?`), a URL, or URI.
     * @param bool   $convert_dots_spaces Optional. This defaults to a `TRUE` value.
     *
     * @return array An array of query string args; based on the input `$qs_url_uri` value.
     */
    protected function urlQueryParseRaw($qs_url_uri, $convert_dots_spaces = true)
    {
        return $this->urlQueryParse($qs_url_uri, $convert_dots_spaces, $this::RFC3986);
    }

    /**
     * Add query arg(s) to a URL.
     *
     * @since 150424 Initial release.
     *
     * @param array  $new_args    Query args to add (not URL-encoded).
     * @param string $url_uri_qsl Input URL, URI, or query string w/ a leading `?`.
     *
     * @return string Input `$url_uri_qsl` with new query arg(s).
     */
    protected function urlQueryAddArgs(array $new_args, $url_uri_qsl)
    {
        $url_uri_qsl = (string) $url_uri_qsl;
        $url_uri_qsl = $this->urlParse($url_uri_qsl);
        $args        = array(); // Initialize.

        if (!empty($url_uri_qsl['query'])) {
            $args = $this->urlQueryParse($url_uri_qsl['query']);
        }
        $args                 = array_merge($args, $new_args);
        $url_uri_qsl['query'] = $this->urlQueryBuild($args);
        $url_uri_qsl          = $this->urlParseUn($url_uri_qsl);

        return $url_uri_qsl;
    }

    /**
     * Remove query arg(s) from a URL.
     *
     * @since 150424 Initial release.
     *
     * @param array  $arg_keys    Query args to remove (keys only).
     * @param string $url_uri_qsl Input URL, URI, or query string w/ a leading `?`.
     *
     * @return string Input `$url_uri_qsl` without query arg(s).
     */
    protected function urlQueryRemoveArgs(array $arg_keys, $url_uri_qsl)
    {
        $url_uri_qsl = (string) $url_uri_qsl;
        $url_uri_qsl = $this->urlParse($url_uri_qsl);
        $args        = array(); // Initialize.

        if (!empty($url_uri_qsl['query'])) {
            $args = $this->urlQueryParse($url_uri_qsl['query']);
        }
        $args                 = array_diff_key($args, $arg_keys);
        $url_uri_qsl['query'] = $this->urlQueryBuild($args);
        $url_uri_qsl          = $this->urlParseUn($url_uri_qsl);

        return $url_uri_qsl;
    }

    /**
     * URL without a query string.
     *
     * @since 150424 Initial release.
     *
     * @param string $url_uri_qsl Input URL, URI, or query string w/ a leading `?`.
     *
     * @return string Input `$url_uri_qsl` without a query string.
     */
    protected function urlQueryStrip($url_uri_qsl)
    {
        $url_uri_qsl = (string) $url_uri_qsl;
        if (strpos($url_uri_qsl, '?') !== false) {
            $url_uri_qsl = strstr($url_uri_qsl, '?', true);
        }
        return $url_uri_qsl;
    }

    /**
     * Generates a URL-encoded query string.
     *
     * @since 150424 Initial release.
     *
     * @param array       $args           Input array of query args.
     * @param null|string $numeric_prefix Optional. Defaults to a `NULL` value.
     * @param null|string $arg_separator  Optional. Defaults to `&`. Use `NULL` for INI `arg_separator.output`.
     * @param null|string $enc_type       Optional. Defaults to {@link RFC1738} indicating `urlencode()`.
     *                                    Or, {@link RFC3986} indicating `rawurlencode()`.
     * @param null|string $___nested_key  For internal use only.
     *
     * @return string A (possibly raw) URL-encoded query string (without a leading `?`).
     */
    protected function urlQueryBuild(array $args, $numeric_prefix = null, $arg_separator = '&', $enc_type = self::RFC1738, $___nested_key = null)
    {
        if (isset($numeric_prefix)) {
            $numeric_prefix = (string) $numeric_prefix;
        }
        if (!isset($arg_separator)) {
            $arg_separator = ini_get('arg_separator.output');
        }
        $arg_separator = (string) $arg_separator;

        $arg_pairs = array(); // Initialize pairs.

        foreach ($args as $_key => $_value) {
            if ($_value === null) {
                continue; // Exclude.
            } elseif ($_value === false) {
                $_value = '0';
            }
            if (isset($___nested_key)) {
                $_key = $___nested_key.'['.$_key.']';
            } elseif (isset($numeric_prefix) && is_numeric($_key)) {
                $_key = $numeric_prefix.$_key;
            }
            if (is_array($_value) || is_object($_value)) {
                if (strlen($_nested_value = $this->urlQueryBuild($_value, null, $arg_separator, $enc_type, $_key))) {
                    $arg_pairs[] = $_nested_value;
                }
            } elseif ($enc_type === $this::RFC1738) {
                $arg_pairs[] = urlencode($_key).'='.urlencode((string) $_value);
            } elseif ($enc_type === $this::RFC3986) {
                $arg_pairs[] = rawurlencode($_key).'='.rawurlencode((string) $_value);
            } else {
                $arg_pairs[] = $_key.'='.(string) $_value;
            }
        }
        unset($_key, $_value, $_nested_value); // Housekeeping.

        return $arg_pairs ? implode($arg_separator, $arg_pairs) : '';
    }

    /**
     * Generates a raw URL-encoded query string.
     *
     * @since 150424 Initial release.
     *
     * @note This method is an alias for {@link urlQueryBuild()} with `$enc_type` set to: {@link RFC3986}.
     *    Please check the {@link urlQueryBuild()} method for further details.
     *
     * @param array       $args           Input array of query args.
     * @param null|string $numeric_prefix Optional. Defaults to a `NULL` value.
     * @param null|string $separator      Optional. Defaults to `&`. Use `NULL` for INI `arg_separator.output`.
     *
     * @return string A raw URL-encoded query string (without a leading `?`).
     */
    protected function urlQueryBuildRaw(array $args, $numeric_prefix = null, $arg_separator = '&')
    {
        return $this->urlQueryBuild($args, $prefix, $separator, $this::RFC3986);
    }
}
