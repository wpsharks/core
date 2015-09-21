<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * SHA signature utilities.
 *
 * @since 150424 Initial release.
 */
class ShaSignatures extends AbsBase
{
    protected $Trim;
    protected $UrlQuery;
    protected $ArraySort;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(
        Trim $Trim,
        UrlQuery $UrlQuery,
        ArraySort $ArraySort
    ) {
        parent::__construct();

        $this->Trim      = $Trim;
        $this->UrlQuery  = $UrlQuery;
        $this->ArraySort = $ArraySort;
    }

    /**
     * Gets SHA-X sig key.
     *
     * @since 150424 Initial release.
     *
     * @param string $key Defaults to `$_SERVER['SHAX_SIG_KEY']`.
     *
     * @return string SHA-X signature key.
     */
    public function xKey($key = '')
    {
        $key = (string) $key;

        if (!$key && !empty($_SERVER['SHAX_SIG_KEY'])) {
            $key = (string) $_SERVER['SHAX_SIG_KEY'];
        }
        if (!$key) {
            throw new \Exception('Missing SHA-X key.');
        }
        return $key;
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
    public function x256($string, $key = '')
    {
        $string = (string) $string;
        $key    = $this->xKey((string) $key);

        return hash_hmac('sha256', $string, $key);
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
    public function x256QueryAdd($url_uri_qsl, $key = '', $sig_var = '')
    {
        $url_uri_qsl = (string) $url_uri_qsl;
        $key         = $this->xKey((string) $key);

        if (!($sig_var = (string) $sig_var)) {
            $sig_var = 'sig';
        }
        $sig         = $this->x256Query($url_uri_qsl, $key, $sig_var);
        $url_uri_qsl = $this->UrlQuery->addArgs([$sig_var => $sig], $url_uri_qsl);

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
    public function x256QueryRemove($url_uri_qsl, $sig_var = '')
    {
        $url_uri_qsl = (string) $url_uri_qsl;

        if (!($sig_var = (string) $sig_var)) {
            $sig_var = 'sig';
        }
        $url_uri_qsl = $this->UrlQuery->removeArgs([$sig_var], $url_uri_qsl);

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
    public function x256QueryOk($qs_url_uri, $key = '', $sig_var = '')
    {
        $qs_url_uri = (string) $qs_url_uri;
        $key        = $this->xKey((string) $key);

        if (!($sig_var = (string) $sig_var)) {
            $sig_var = 'sig';
        }
        $args = $this->UrlQuery->parse($qs_url_uri);
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
    public function x256Query($qs_url_uri, $key = '', $sig_var = '')
    {
        $qs_url_uri = (string) $qs_url_uri;
        $key        = $this->xKey((string) $key);

        if (!($sig_var = (string) $sig_var)) {
            $sig_var = 'sig';
        }
        $args = $this->UrlQuery->parse($qs_url_uri);
        unset($args[$sig_var]); // Exclude.

        $args = $this->Trim($args);
        $args = $this->ArraySort->byKey($args);
        $sig  = $this->x256(serialize($args), $key);

        return $sig; // Signature.
    }
}
