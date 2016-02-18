<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Functions\__;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Base64 encryption utilities.
 *
 * @since 150424 Initial release.
 */
class Base64 extends Classes\AppBase
{
    /**
     * Base64 URL-safe encoding.
     *
     * @since 150424 Initial release.
     *
     * @param string $string             Input string to be base64 encoded.
     * @param array  $url_unsafe_chars   Optional array of un-safe characters. Defaults to: `['+', '/']`.
     * @param array  $url_safe_chars     Optional array of safe character replacements. Defaults to: `['-', '_']`.
     * @param string $trim_padding_chars Optional string of padding chars to right-trim. Defaults to: `=`.
     *
     * @throws Exception If the call to `base64_encode()` fails.
     *
     * @return string The base64 URL-safe encoded string.
     */
    public function urlSafeEncode(string $string, array $url_unsafe_chars = ['+', '/'], array $url_safe_chars = ['-', '_'], string $trim_padding_chars = '='): string
    {
        if (!is_string($base64_url_safe = base64_encode($string))) {
            throw new Exception('Base64 encoding failure.');
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
     * @param array  $url_unsafe_chars   Optional array of un-safe characters. Defaults to: `['+', '/']`.
     * @param array  $url_safe_chars     Optional array of safe character replacements. Defaults to: `['-', '_']`.
     * @param string $trim_padding_chars Optional string of padding chars to right-trim. Defaults to: `=`.
     *
     * @throws Exception If the call to `base64_decode()` fails.
     *
     * @return string The decoded string value.
     */
    public function urlSafeDecode(string $base64_url_safe, array $url_unsafe_chars = ['+', '/'], array $url_safe_chars = ['-', '_'], string $trim_padding_chars = '='): string
    {
        $string = isset($trim_padding_chars[0]) ? rtrim($base64_url_safe, $trim_padding_chars) : $base64_url_safe;
        $string = isset($trim_padding_chars[0]) ? str_pad($string, strlen($string) % 4, '=', STR_PAD_RIGHT) : $string;
        $string = str_replace($url_safe_chars, $url_unsafe_chars, $string);

        if (!is_string($string = base64_decode($string, true))) {
            throw new Exception('Base64 decoding failure.');
        }
        return $string;
    }
}
