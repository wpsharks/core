<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;

/**
 * SHA signature utilities.
 *
 * @since 150424 Initial release.
 */
class ShaSignatures extends Classes\AbsBase
{
    /**
     * Gets SHA-X sig key.
     *
     * @since 150424 Initial release.
     *
     * @param string $key The signature key.
     *
     * @return string SHA-X signature key.
     */
    public function xKey(string $key = ''): string
    {
        if ($key) {
            return $key; // Highest precedent!
        }
        if (defined(__NAMESPACE__.'\\SHAX_SIG_KEY') && SHAX_SIG_KEY) {
            return (string) SHAX_SIG_KEY; // Higher precedent.
        }
        if (!empty($_SERVER['SHAX_SIG_KEY'])) {
            return (string) $_SERVER['SHAX_SIG_KEY'];
        }
        throw new Exception('Missing SHA-X key.');
    }

    /**
     * Generates a keyed SHA-256 signature.
     *
     * @since 150424 Initial release.
     *
     * @param string $string String to sign.
     * @param string $key    Defaults to `$_SERVER['SHAX_SIG_KEY']`.
     *
     * @return string SHA-256 signature string.
     */
    public function x256(string $string, string $key = ''): string
    {
        return hash_hmac('sha256', $string, $this->xKey($key));
    }

    /**
     * Adds a keyed SHA-256 signature.
     *
     * @since 150424 Initial release.
     *
     * @param string $url_uri_qsl Input URL, URI, or query string w/ a leading `?`.
     * @param string $key         Defaults to `$_SERVER['SHAX_SIG_KEY']`.
     * @param string $sig_var     Optional. Defaults to `sig`.
     *
     * @return string Input `$url_uri_qsl` w/ a SHA-256 signature var.
     */
    public function x256QueryAdd(string $url_uri_qsl, string $key = '', string $sig_var = ''): string
    {
        if (!$sig_var) {
            $sig_var = 'sig';
        }
        $key         = $this->xKey($key);
        $sig         = $this->x256Query($url_uri_qsl, $key, $sig_var);
        $url_uri_qsl = $this->Utils->UrlQuery->addArgs([$sig_var => $sig], $url_uri_qsl);

        return $url_uri_qsl; // With signature.
    }

    /**
     * Removes a keyed SHA-256 signature.
     *
     * @since 150424 Initial release.
     *
     * @param string $url_uri_qsl Input URL, URI, or query string w/ a leading `?`.
     * @param string $sig_var     Optional. Defaults to `sig`.
     *
     * @return string Input `$url_uri_qsl` w/o a SHA-256 signature var.
     */
    public function x256QueryRemove(string $url_uri_qsl, string $sig_var = ''): string
    {
        if (!$sig_var) {
            $sig_var = 'sig';
        }
        $url_uri_qsl = $this->Utils->UrlQuery->removeArgs([$sig_var], $url_uri_qsl);

        return $url_uri_qsl; // Without signature.
    }

    /**
     * Checks a keyed SHA-256 signature.
     *
     * @since 150424 Initial release.
     *
     * @param string $qs_url_uri A query string (with or without a leading `?`), a URL, or URI.
     * @param string $key        Defaults to `$_SERVER['SHAX_SIG_KEY']`.
     * @param string $sig_var    Optional. Defaults to `sig`.
     *
     * @return bool `TRUE` if a valid signature exists.
     */
    public function x256QueryOk(string $qs_url_uri, string $key = '', string $sig_var = ''): bool
    {
        if (!$sig_var) {
            $sig_var = 'sig';
        }
        $key  = $this->xKey($key);
        $args = $this->Utils->UrlQuery->parse($qs_url_uri);
        $sig  = $this->x256Query($qs_url_uri);

        return !empty($args[$sig_var]) && $args[$sig_var] === $sig;
    }

    /**
     * Builds a keyed SHA-256 signature.
     *
     * @since 150424 Initial release.
     *
     * @param string $qs_url_uri A query string (with or without a leading `?`), a URL, or URI.
     * @param string $key        Defaults to `$_SERVER['SHAX_SIG_KEY']`.
     * @param string $sig_var    Optional. Defaults to `sig`.
     *
     * @return string SHA-256 signature string.
     */
    public function x256Query(string $qs_url_uri, string $key = '', string $sig_var = ''): string
    {
        if (!$sig_var) {
            $sig_var = 'sig';
        }
        $key  = $this->xKey($key);
        $args = $this->Utils->UrlQuery->parse($qs_url_uri);
        unset($args[$sig_var]); // Exclude.

        $args = $this->Utils->Trim($args);
        $args = $this->Utils->ArraySort->byKey($args);
        $sig  = $this->x256(serialize($args), $key);

        return $sig; // Signature.
    }
}
