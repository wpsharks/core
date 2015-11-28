<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * SHA signature utilities.
 *
 * @since 150424 Initial release.
 */
class ShaSignatures extends Classes\AbsBase
{
    /**
     * Generates a keyed SHA-256 signature.
     *
     * @since 150424 Initial release.
     *
     * @param string $string String to sign.
     * @param string $key    Encryption key.
     *
     * @return string SHA-256 signature string.
     */
    public function x256(string $string, string $key): string
    {
        return hash_hmac('sha256', $string, $key);
    }

    /**
     * Adds a keyed SHA-256 signature.
     *
     * @since 150424 Initial release.
     *
     * @param string $url_uri_qsl Input URL, URI, or query string w/ a leading `?`.
     * @param string $key         Encryption key.
     * @param string $sig_var     Optional. Defaults to `sig`.
     *
     * @return string Input `$url_uri_qsl` w/ a SHA-256 signature var.
     */
    public function x256QueryAdd(string $url_uri_qsl, string $key, string $sig_var = ''): string
    {
        $sig_var     = !$sig_var ? 'sig' : $sig_var;
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
        $sig_var     = !$sig_var ? 'sig' : $sig_var;
        $url_uri_qsl = $this->Utils->UrlQuery->removeArgs([$sig_var], $url_uri_qsl);

        return $url_uri_qsl; // Without signature.
    }

    /**
     * Checks a keyed SHA-256 signature.
     *
     * @since 150424 Initial release.
     *
     * @param string $qs_url_uri A query string (with or without a leading `?`), a URL, or URI.
     * @param string $key        Encryption key.
     * @param string $sig_var    Optional. Defaults to `sig`.
     *
     * @return bool `TRUE` if a valid signature exists.
     */
    public function x256QueryOk(string $qs_url_uri, string $key, string $sig_var = ''): bool
    {
        $sig_var = !$sig_var ? 'sig' : $sig_var;
        $args    = $this->Utils->UrlQuery->parse($qs_url_uri);
        $sig     = $this->x256Query($qs_url_uri);

        return !empty($args[$sig_var]) && $args[$sig_var] === $sig;
    }

    /**
     * Builds a keyed SHA-256 signature.
     *
     * @since 150424 Initial release.
     *
     * @param string $qs_url_uri A query string (with or without a leading `?`), a URL, or URI.
     * @param string $key        Encryption key.
     * @param string $sig_var    Optional. Defaults to `sig`.
     *
     * @return string SHA-256 signature string.
     */
    public function x256Query(string $qs_url_uri, string $key, string $sig_var = ''): string
    {
        $sig_var = !$sig_var ? 'sig' : $sig_var;
        $args    = $this->Utils->UrlQuery->parse($qs_url_uri);
        unset($args[$sig_var]); // Exclude.

        $args = $this->Utils->Trim($args);
        $args = $this->Utils->ArraySort->byKey($args);
        $sig  = $this->x256(serialize($args), $key);

        return $sig; // Signature.
    }
}
