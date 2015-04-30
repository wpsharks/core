<?php
namespace WebSharks\Core\Traits;

/**
 * URL Query Utilities.
 *
 * @since 150424 Initial release.
 */
trait UrlQueryUtils
{
    abstract protected function parseUrl($url, $component = -1);
    abstract protected function unparseUrl(array $parts);

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
     * @return string A (possibly raw) URL-encoded query string.
     */
    protected function buildUrlQuery(array $args, $numeric_prefix = null, $arg_separator = '&', $enc_type = self::RFC1738, $___nested_key = null)
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
                if (strlen($_nested_value = $this->buildUrlQuery($_value, null, $arg_separator, $enc_type, $_key))) {
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
     * @note This method is an alias for {@link buildUrlQuery()} with `$enc_type` set to: {@link RFC3986}.
     *    Please check the {@link buildUrlQuery()} method for further details.
     *
     * @param array       $args           Input array of query args.
     * @param null|string $numeric_prefix Optional. Defaults to a `NULL` value.
     * @param null|string $separator      Optional. Defaults to `&`. Use `NULL` for INI `arg_separator.output`.
     *
     * @return string A raw URL-encoded query string.
     */
    protected function buildRawUrlQuery(array $args, $numeric_prefix = null, $arg_separator = '&')
    {
        return $this->buildUrlQuery($args, $prefix, $separator, $this::RFC3986);
    }

    /**
     * Generates an array from a string of query vars.
     *
     * @since 150424 Initial release.
     *
     * @param string      $string              An input string of query vars.
     * @param bool        $convert_dots_spaces Defaults to `TRUE` (just like PHP's {@link parse_str()} function).
     * @param null|string $dec_type            Optional. Defaults to {@link RFC1738} indicating `urldecode()`.
     *                                         Or, {@link RFC3986} indicating `rawurldecode()`.
     * @param null|array  $___parent_array     Internal use only; for recursion.
     *
     * @return array An array of data, based on the input `$string` value.
     */
    protected function parseUrlQuery($string, $convert_dots_spaces = true, $dec_type = self::RFC1738, &$___parent_array = null)
    {
        $string = (string) $string;

        if (isset($___parent_array)) {
            $array = &$___parent_array;
        } else {
            $array = array();
        }
        foreach (explode('&', $string) as $_name_value) {
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
                $array[$_m['name']] = $this->parseUrlQuery($_value, $convert_dots_spaces, $dec_type, $array[$_m['name']]);
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
     * @note This method is an alias for {@link parseUrlQuery()} with `$enc_type` set to: {@link PHP_QUERY_RFC3986}.
     *    Please check the {@link parseUrlQuery()} method for further details.
     *
     * @param string $string              An input string of query vars.
     * @param bool   $convert_dots_spaces Optional. This defaults to a `TRUE` value.
     *
     * @return array An array of data, based on the input `$string` value.
     */
    protected function parseRawUrlQuery($string, $convert_dots_spaces = true)
    {
        return $this->parseUrlQuery($string, $convert_dots_spaces, $this::RFC3986);
    }

    /**
     * Add query arg(s) to a URL.
     *
     * @since 150424 Initial release.
     *
     * @param array  $new_args Query args to add (not URL-encoded).
     * @param string $url      The input URL to work from.
     *
     * @return string URL with new query arg(s).
     */
    protected function addUrlQueryArgs(array $new_args, $url)
    {
        $url        = (string) $url;
        $url        = $this->parseUrl($url);
        $args       = array(); // Initialize.

        if (!empty($url['query'])) {
            $args = $this->parseUrlQuery($url['query']);
        }
        $args         = array_merge($args, $new_args);
        $url['query'] = $this->buildUrlQuery($args);
        $url          = $this->unparseUrl($url);

        return $url;
    }

    /**
     * Remove query arg(s) from a URL.
     *
     * @since 150424 Initial release.
     *
     * @param array  $arg_keys Query args to remove (keys only).
     * @param string $url      The input URL to work from.
     *
     * @return string URL without query arg(s).
     */
    protected function removeUrlQueryArgs(array $arg_keys, $url)
    {
        $url        = (string) $url;
        $url        = $this->parseUrl($url);
        $args       = array(); // Initialize.

        if (!empty($url['query'])) {
            $args = $this->parseUrlQuery($url['query']);
        }
        $args         = array_diff_key($args, $arg_keys);
        $url['query'] = $this->buildUrlQuery($args);
        $url          = $this->unparseUrl($url);

        return $url;
    }

    /**
     * URL without a query string.
     *
     * @since 150424 Initial release.
     *
     * @param null|string $url The input URL to work from.
     *
     * @return string URL without a query string.
     */
    protected function stripUrlQuery($url)
    {
        $url = (string) $url;
        if (strpos($url, '?') !== false) {
            $url = strstr($url, '?', true);
        }
        return $url;
    }
}
