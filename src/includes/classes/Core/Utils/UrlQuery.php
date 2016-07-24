<?php
/**
 * URL query utilities.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * URL query utilities.
 *
 * @since 150424 Initial release.
 */
class UrlQuery extends Classes\Core\Base\Core implements Interfaces\UrlConstants
{
    /**
     * Default.
     *
     * @since 150424
     *
     * @var string
     */
    const DEFAULT_SIG_VAR = '_sig';

    /**
     * Stripe query string.
     *
     * @since 150424 Initial release.
     *
     * @param string $url_uri_qsl Input URL, URI, or query string w/ a leading `?`.
     *
     * @return string Input `$url_uri_qsl` without a query string.
     */
    public function strip(string $url_uri_qsl): string
    {
        if (!$qs_url_uri) {
            return $qs_url_uri; // Possible `0`.
        }
        if (mb_strpos($url_uri_qsl, '?') !== false) {
            $url_uri_qsl = mb_strstr($url_uri_qsl, '?', true);
        }
        return $url_uri_qsl;
    }

    /**
     * Array from query vars.
     *
     * @since 150424 Initial release.
     *
     * @param string $qs_url_uri A query string (w/ or w/o a leading `?`), a URL, or URI.
     *
     * @return array An array of args; based on the `$qs_url_uri` value.
     */
    public function parse(string $qs_url_uri): array
    {
        $qs = $this->string($qs_url_uri);

        parse_str($qs, $args); // Possible `0`.
        // Note that `0` becomes `[0 => '']`.

        return $args;
    }

    /**
     * Build a URL-encoded query string.
     *
     * @since 150424 Initial release.
     *
     * @param array       $args           Input array of query args.
     * @param null|string $numeric_prefix Defaults to an empty string.
     * @param string      $arg_separator  Defaults to `&`. Empty = INI `arg_separator.output`.
     * @param int         $enc_type       Defaults to {@link PHP_QUERY_RFC1738} indicating `urlencode()`.
     *                                    Or, {@link PHP_QUERY_RFC3986} indicating `rawurlencode()`.
     *
     * @return string A (possibly raw) URL-encoded query string (w/o a leading `?`).
     */
    public function build(array $args, string $numeric_prefix = '', string $arg_separator = '&', int $enc_type = PHP_QUERY_RFC1738): string
    {
        if (!isset($arg_separator[0])) {
            $arg_separator = ini_get('arg_separator.output');
        }
        $query = http_build_query($args, $numeric_prefix, $arg_separator, $enc_type);
        $query = $this->c::mbTrim(str_replace('=&', '&', $query), '=&');

        return $query;
    }

    /**
     * Add query arg(s) to a URL.
     *
     * @since 150424 Initial release.
     *
     * @param array  $new_args    Query args to add (not URL-encoded).
     * @param string $url_uri_qsl Input URL, URI, or query string w/ a leading `?`.
     *
     * @return string The `$url_uri_qsl` w/ new query arg(s).
     */
    public function addArgs(array $new_args, string $url_uri_qsl): string
    {
        $url_uri_qsl = $this->c::parseUrl($url_uri_qsl);
        $args        = []; // Initialize.

        if (isset($url_uri_qsl['query'][0])) {
            $args = $this->parse($url_uri_qsl['query']);
        }
        $args                 = array_merge($args, $new_args);
        $url_uri_qsl['query'] = $this->build($args);
        $url_uri_qsl          = $this->c::unparseUrl($url_uri_qsl);

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
     * @return string The `$url_uri_qsl` w/o query arg(s).
     */
    public function removeArgs(array $arg_keys, string $url_uri_qsl): string
    {
        $url_uri_qsl = $this->c::parseUrl($url_uri_qsl);
        $args        = []; // Initialize.

        if (isset($url_uri_qsl['query'][0])) {
            $args = $this->parse($url_uri_qsl['query']);
        }
        $arg_keys             = array_fill_keys($arg_keys, null);
        $args                 = array_diff_key($args, $arg_keys);
        $url_uri_qsl['query'] = $this->build($args);
        $url_uri_qsl          = $this->c::unparseUrl($url_uri_qsl);

        return $url_uri_qsl;
    }

    /**
     * Adds a signature.
     *
     * @since 150424 Initial release.
     *
     * @param string $url_uri_qsl Input URL, URI, or query string w/ a leading `?`.
     * @param string $key         Encryption key (optional).
     * @param string $sig_var     Defaults to `sig`.
     *
     * @return string The `$url_uri_qsl` w/ a signature var.
     */
    public function addSha256Sig(string $url_uri_qsl, string $key = '', string $sig_var = ''): string
    {
        $sig_var     = $sig_var ?: $this::DEFAULT_SIG_VAR;
        $sig         = $this->sha256Sig($url_uri_qsl, $key, $sig_var);
        $url_uri_qsl = $this->addArgs([$sig_var => $sig], $url_uri_qsl);

        return $url_uri_qsl;
    }

    /**
     * Removes a signature.
     *
     * @since 150424 Initial release.
     *
     * @param string $url_uri_qsl Input URL, URI, or query string w/ a leading `?`.
     * @param string $sig_var     Defaults to `sig`.
     *
     * @return string The `$url_uri_qsl` w/o a signature.
     */
    public function removeSha256Sig(string $url_uri_qsl, string $sig_var = ''): string
    {
        $sig_var     = $sig_var ?: $this::DEFAULT_SIG_VAR;
        $url_uri_qsl = $this->removeArgs([$sig_var], $url_uri_qsl);

        return $url_uri_qsl;
    }

    /**
     * Checks a signature.
     *
     * @since 150424 Initial release.
     *
     * @param string $qs_url_uri A query string (w/ or w/o a leading `?`), a URL, or URI.
     * @param string $key        Encryption key (optional).
     * @param string $sig_var    Defaults to `sig`.
     *
     * @return bool True if a valid signature exists.
     */
    public function sha256SigOk(string $qs_url_uri, string $key = '', string $sig_var = ''): bool
    {
        $sig_var = $sig_var ?: $this::DEFAULT_SIG_VAR;
        $args    = $this->parse($qs_url_uri);
        $sig     = $this->sha256Sig($qs_url_uri, $key, $sig_var);

        return !empty($args[$sig_var]) && $args[$sig_var] === $sig;
    }

    /**
     * Builds a keyed SHA-256 signature.
     *
     * @since 150424 Initial release.
     *
     * @param string $qs_url_uri A query string (w/ or w/o a leading `?`), a URL, or URI.
     * @param string $key        Encryption key (optional).
     * @param string $sig_var    Defaults to `sig`.
     *
     * @return string SHA-256 signature string.
     */
    protected function sha256Sig(string $qs_url_uri, string $key = '', string $sig_var = ''): string
    {
        if (!$key && !($key = $this->App->Config->©urls['©sig_key'])) {
            throw $this->c::issue('Missing URL signature key.');
        }
        $sig_var = $sig_var ?: $this::DEFAULT_SIG_VAR;
        $args    = $this->parse($qs_url_uri);
        unset($args[$sig_var]); // Exclude.

        $args            = $this->c::mbTrim($args);
        $args            = $this->c::sortByKey($args);
        $serialized_args = serialize($args); // After sorting by key.
        $sig             = $this->c::sha256KeyedHash($serialized_args, $key);

        return $sig; // Signature.
    }

    /**
     * Acquire/sanitize query string.
     *
     * @since 150424 Improving query strings.
     *
     * @param string $qs_url_uri A query string (w/ or w/o a leading `?`), a URL, or URI.
     *
     * @return string Query string (no leading `?`); w/ normalized ampersands.
     */
    protected function string(string $qs_url_uri): string
    {
        if (!$qs_url_uri) {
            return $qs_url_uri; // Possible `0`.
        }
        $qs_url_uri = $this->c::normalizeUrlAmps($qs_url_uri);

        if (mb_strpos($qs_url_uri, '?') !== false) {
            list(, $qs) = explode('?', $qs_url_uri, 2);
        } elseif ($qs_url_uri[0] === '/' || preg_match('/^'.$this::URL_REGEX_FRAG_SCHEME.'/u', $qs_url_uri)) {
            $qs = ''; // There is no query string in this case.
        } else {
            $qs = $qs_url_uri; // Assume it's a query string.
        }
        return $qs = $this->c::mbTrim($qs, '', '?=&');
    }
}
