<?php
namespace WebSharks\Core\Traits;

/**
 * Base64 encryption utilities.
 *
 * @since 150424 Initial release.
 */
trait EncBase64Utils
{
    /**
     * Base64 URL-safe encoding.
     *
     * @since 150424 Initial release.
     *
     * @param string $string             Input string to be base64 encoded.
     * @param array  $url_unsafe_chars   Optional array of un-safe characters. Defaults to: `array('+', '/')`.
     * @param array  $url_safe_chars     Optional array of safe character replacements. Defaults to: `array('-', '_')`.
     * @param string $trim_padding_chars Optional string of padding chars to rtrim. Defaults to: `=`.
     *
     * @throws \exception If the call to `base64_encode()` fails.
     *
     * @return string The base64 URL-safe encoded string.
     */
    protected function encBase64UrlSafeEncode($string, array $url_unsafe_chars = array('+', '/'), array $url_safe_chars = array('-', '_'), $trim_padding_chars = '=')
    {
        $string             = (string) $string;
        $trim_padding_chars = (string) $trim_padding_chars;

        if (!is_string($base64_url_safe = base64_encode($string))) {
            throw new \exception('Base64 encoding failed (`$base64_url_safe` is NOT a string).');
        }
        $base64_url_safe = str_replace($url_unsafe_chars, $url_safe_chars, $base64_url_safe);
        $base64_url_safe = isset($trim_padding_chars[0]) ? rtrim($base64_url_safe, $trim_padding_chars) : $base64_url_safe;

        return $base64_url_safe;
    }

    /**
     * Base64 URL-safe decoding.
     *
     * @since 150424 Initial release.
     *
     * @param string $base64_url_safe    Input string to be base64 decoded.
     * @param array  $url_unsafe_chars   Optional array of un-safe characters. Defaults to: `array('+', '/')`.
     * @param array  $url_safe_chars     Optional array of safe character replacements. Defaults to: `array('-', '_')`.
     * @param string $trim_padding_chars Optional string of padding chars to rtrim. Defaults to: `=`.
     *
     * @throws \exception If the call to `base64_decode()` fails.
     *
     * @return string The decoded string value.
     */
    protected function encBase64UrlSafeDecode($base64_url_safe, array $url_unsafe_chars = array('+', '/'), array $url_safe_chars = array('-', '_'), $trim_padding_chars = '=')
    {
        $base64_url_safe    = (string) $base64_url_safe;
        $trim_padding_chars = (string) $trim_padding_chars;

        $string = isset($trim_padding_chars[0]) ? rtrim($base64_url_safe, $trim_padding_chars) : $base64_url_safe;
        $string = isset($trim_padding_chars[0]) ? str_pad($string, strlen($string) % 4, '=', STR_PAD_RIGHT) : $string;
        $string = str_replace($url_safe_chars, $url_unsafe_chars, $string);

        if (!is_string($string = base64_decode($string, true))) {
            throw new \exception('Base64 decoding failed (`$string` is NOT a string).');
        }
        return $string;
    }
}
