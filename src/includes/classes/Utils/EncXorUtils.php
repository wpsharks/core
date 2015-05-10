<?php
namespace WebSharks\Core\Traits;

/**
 * XOR encryption utilities.
 *
 * @since 150424 Initial release.
 */
trait EncXorUtils
{
    abstract protected function encShaXSigKey($key = '');
    abstract protected function encBase64UrlSafeEncode($string, array $url_unsafe_chars = array('+', '/'), array $url_safe_chars = array('-', '_'), $trim_padding_chars = '=');
    abstract protected function encBase64UrlSafeDecode($base64_url_safe, array $url_unsafe_chars = array('+', '/'), array $url_safe_chars = array('-', '_'), $trim_padding_chars = '=');

    /**
     * XOR encryption with a base64 wrapper.
     *
     * @since 150424 Initial release.
     *
     * @param string $string   A string of data to encrypt.
     * @param string $key      Optional. Key to use in encryption.
     * @param bool   $w_md5_cs Optional. Defaults to `TRUE` (recommended).
     *
     * @throws \Exception If string encryption fails.
     *
     * @return string Encrypted string.
     */
    protected function encXorEncrypt($string, $key = '', $w_md5_cs = true)
    {
        $string = (string) $string;
        if (!isset($string[0])) {
            return ($base64 = '');
        }
        $string = '~xe|'.$string; // Identifier.
        $key    = $this->encShaXSigKey((string) $key);

        for ($_length = strlen($string), $_key_length = strlen($key), $_i = 1, $e = ''; $_i <= $_length; $_i++) {
            $_char     = (string) substr($string, $_i - 1, 1);
            $_key_char = (string) substr($key, ($_i % $_key_length) - 1, 1);
            $e .= chr(ord($_char) + ord($_key_char));
        }
        unset($_length, $_key_length, $_i, $_char, $_key_char); // Housekeeping.

        if (!isset($e[0])) {
            throw new \Exception('String encryption failed; `$e` has no length.');
        }
        $e = '~xe'.($w_md5_cs ? ':'.md5($e) : '').'|'.$e; // Pack components.

        return ($base64 = $this->encBase64UrlSafeEncode($e));
    }

    /**
     * XOR decryption with a base64 unwrapper.
     *
     * @since 150424 Initial release.
     *
     * @param string $base64 A string of data to decrypt (still base64 encoded).
     * @param string $key    Optional. Key originally used for encryption.
     *
     * @throws \Exception If a validated XOR string decryption fails.
     *
     * @return string Decrypted string, or an empty string if validation fails.
     */
    protected function encXorDecrypt($base64, $key = '')
    {
        $base64 = (string) $base64;
        if (!isset($base64[0])) {
            return ($string = '');
        }
        $key = $this->encShaXSigKey((string) $key);

        if (!strlen($e = $this->encBase64UrlSafeDecode($base64))
           || !preg_match('/^~xe(?:\:(?P<md5>[a-zA-Z0-9]+))?\|(?P<e>.*)$/s', $e, $md5_e)
        ) {
            return ($string = ''); // Not possible; unable to decrypt in this case.
        }
        if (!isset($md5_e['e'][0])) {
            return ($string = ''); // Components missing.
        }
        if (isset($md5_e['md5'][0]) && $md5_e['md5'] !== md5($md5_e['e'])) {
            return ($string = ''); // Invalid checksum; automatic failure.
        }
        for ($_length = strlen($md5_e['e']), $_key_length = strlen($key), $_i = 1, $string = ''; $_i <= $_length; $_i++) {
            $_char     = (string) substr($md5_e['e'], $_i - 1, 1);
            $_key_char = (string) substr($key, ($_i % $_key_length) - 1, 1);
            $string .= chr(ord($_char) - ord($_key_char));
        }
        unset($_length, $_key_length, $_i, $_char, $_key_char); // Housekeeping.

        if (!isset($string[0])) {
            throw new \Exception('String decryption failed; `$string` has no length.');
        }
        if (!strlen($string = preg_replace('/^~xe\|/', '', $string, 1, $xe)) || !$xe) {
            return ($string = '');  // Missing packed components.
        }
        return $string; // Decrypted string now.
    }
}
