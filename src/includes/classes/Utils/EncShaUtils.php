<?php
namespace WebSharks\Core\Classes\Utils;

/**
 * Enc. SHA utilities.
 *
 * @since 150424 Initial release.
 */
class EncShaUtils extends AbsBase
{
    abstract public function urlQueryAddArgs(array $new_args, $url_uri_qsl);
    abstract public function urlQueryRemoveArgs(array $arg_keys, $url_uri_qsl);
    abstract public function urlQueryParse($qs_url_uri, $convert_dots_spaces = true, $dec_type = self::RFC1738, &$___parent_array = null);
    abstract public function trim($value, $chars = '', $extra_chars = '', $side = '');
    abstract public function arraySortByKey(array $array, $flags = SORT_REGULAR);

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
     * Gets SHA-X sig key.
     *
     * @since 150424 Initial release.
     *
     * @param string $key Defaults to `$_SERVER['SHAX_SIG_KEY']`.
     *
     * @return string SHA-X signature key.
     */
    public function encShaXSigKey($key = '')
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
    public function encSha256Sig($string, $key = '')
    {
        $string = (string) $string;
        $key    = $this->encShaXSigKey((string) $key);

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
    public function encSha256AddQuerySig($url_uri_qsl, $key = '', $sig_var = '')
    {
        $url_uri_qsl = (string) $url_uri_qsl;
        $key         = $this->encShaXSigKey((string) $key);

        if (!($sig_var = (string) $sig_var)) {
            $sig_var = 'sig';
        }
        $sig         = $this->encSha256QuerySig($url_uri_qsl, $key, $sig_var);
        $url_uri_qsl = $this->urlQueryAddArgs([$sig_var => $sig], $url_uri_qsl);

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
    public function encSha256RemoveQuerySig($url_uri_qsl, $sig_var = '')
    {
        $url_uri_qsl = (string) $url_uri_qsl;

        if (!($sig_var = (string) $sig_var)) {
            $sig_var = 'sig';
        }
        $url_uri_qsl = $this->urlQueryRemoveArgs([$sig_var], $url_uri_qsl);

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
    public function encSha256QuerySigOk($qs_url_uri, $key = '', $sig_var = '')
    {
        $qs_url_uri = (string) $qs_url_uri;
        $key        = $this->encShaXSigKey((string) $key);

        if (!($sig_var = (string) $sig_var)) {
            $sig_var = 'sig';
        }
        $args = $this->urlQueryParse($qs_url_uri);
        $sig  = $this->encSha256QuerySig($qs_url_uri);

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
    public function encSha256QuerySig($qs_url_uri, $key = '', $sig_var = '')
    {
        $qs_url_uri = (string) $qs_url_uri;
        $key        = $this->encShaXSigKey((string) $key);

        if (!($sig_var = (string) $sig_var)) {
            $sig_var = 'sig';
        }
        $args = $this->urlQueryParse($qs_url_uri);
        unset($args[$sig_var]); // Exclude.

        $args = $this->trim($args);
        $args = $this->arraySortByKey($args);
        $sig  = $this->encSha256Sig(serialize($args), $key);

        return $sig; // Signature.
    }
}
