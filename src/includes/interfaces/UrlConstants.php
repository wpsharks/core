<?php
declare (strict_types = 1);
namespace WebSharks\Core\Interfaces;

/**
 * URL-related constants.
 *
 * @since 150424 Initial release.
 */
interface UrlConstants
{
    /**
     * Regex matches a `[scheme:]//`.
     *
     * @since 150424 Initial release.
     *
     * @type string Regex fragment for use in `preg_match()`.
     *
     * @see http://jas.xyz/1TjZ0kA for details about valid schemes.
     */
    const URL_REGEX_FRAG_SCHEME = '(?:[a-zA-Z][a-zA-Z0-9+.\-]*\:)?\/\/';

    /**
     * Regex matches `[user:pass@]`.
     *
     * @since 150424 Initial release.
     *
     * @type string Regex fragment for use in `preg_match()`.
     *
     * @see RegexConstants::REGEX_FRAG_NOT_ASCII_OR_INVISIBLE
     */
    const URL_REGEX_FRAG_USER_PASS = '(?:'.
        '(?:[^\x{00}-\x{7F}\p{Z}\p{C}]|[a-zA-Z0-9_.~+%\-])+'.// user
        '(?:\:(?:[^\x{00}-\x{7F}\p{Z}\p{C}]|[a-zA-Z0-9_.~+%\-])+)?'.// :pass
        '@'.// @
    ')?'; // All optional.

    /**
     * Regex matches a `host` name.
     *
     * @since 150424 Initial release.
     *
     * @type string Regex fragment for use in `preg_match()`.
     */
    const URL_REGEX_FRAG_HOST = // Punycode-compatible.
        '(?:xn\-\-)?[a-zA-Z0-9]+(?:\-*[a-zA-Z0-9]+)*'.// `[xn--]a[-b][-c]`
            '(?:\.(?:xn\-\-)?[a-zA-Z0-9]+(?:\-*[a-zA-Z0-9]+)*)*?';// `.[xn--]a[-b][-c]`

    /**
     * Regex matches a `host.tld` name.
     *
     * @since 150424 Initial release.
     *
     * @type string Regex fragment for use in `preg_match()`.
     */
    const URL_REGEX_FRAG_HOST_TLD = // Punycode-compatible.
        self::URL_REGEX_FRAG_HOST.// `[xn--]a[-b][-c].[xn--]a[-b][-c]`
        '(?:\.(?:xn\-\-)?[a-zA-Z][a-zA-Z0-9]+)';// `.[xn--]tld`

    /**
     * Regex matches a `[:port]`.
     *
     * @since 150424 Initial release.
     *
     * @type string Regex fragment for use in `preg_match()`.
     */
    const URL_REGEX_FRAG_PORT = '(?:\:[0-9]+)?';

    /**
     * Regex matches a `host.tld[:port]` (punycode-compatible).
     *
     * @since 150424 Initial release.
     *
     * @type string Regex fragment for use in `preg_match()`.
     */
    const URL_REGEX_FRAG_HOST_TLD_PORT = self::URL_REGEX_FRAG_HOST_TLD.self::URL_REGEX_FRAG_PORT;

    /**
     * Regex matches a `[/path][?query][#hash]`.
     *
     * @since 150424 Initial release.
     *
     * @type string Regex fragment for use in `preg_match()`.
     *
     * @see RegexConstants::REGEX_FRAG_NOT_ASCII_OR_INVISIBLE
     */
    const URL_REGEX_FRAG_URI_HASH = // Very complex.

        '(?:\/(?!\/)'.// A slash `/`, but not two `//` together.
            '(?:[^\x{00}-\x{7F}\p{Z}\p{C}]|[a-zA-Z0-9=_.~+%\-])*'.// `path` name.
        ')*'.// The `[/paths]` are completely optional at all times.

        '(?:\?'.// Possible query string; i.e., `?[key[=value]]`

            '(?:'.// Match first `[key[=value]]` pair.
                '(?:[^\x{00}-\x{7F}\p{Z}\p{C}]|[a-zA-Z0-9_.~+%\-])+'.
                '(?:\=(?:[^\x{00}-\x{7F}\p{Z}\p{C}]|[a-zA-Z0-9_.~+%\-])*)?'.
            ')?'.// Optional; i.e., allow for `?&key=value` here.

            '(?:&'.// `&[key[=value]]` pairs.
                '(?:'.// The `key=value` is optional.
                    '(?:[^\x{00}-\x{7F}\p{Z}\p{C}]|[a-zA-Z0-9_.~+%\-])+'.
                    '(?:\=(?:[^\x{00}-\x{7F}\p{Z}\p{C}]|[a-zA-Z0-9_.~+%\-])*)?'.
                ')?'.// Allow for `?&` or `&&` with missing keys.
            ')*'.// `&[key[=value]]` pairs 0 or more times.

        ')?'.// The entire query string is always optional.

        '(?:#[^\s&?<>\/]*)?'; // `#hash` (i.e., almost anything).

    /**
     * Regex matches a valid `[scheme:]//[user:pass@]host.tld[:port][/uri][?query][#hash]`.
     *
     * @since 150424 Initial release.
     *
     * @type string Regex pattern for use in `preg_match()`.
     *
     * @note This can be used in MySQL by outputting the following:
     *  `echo str_replace(["'", '\\x', '\\p', '\\?'], ["\\'", '\\\\x', '\\\\p', '\\\\?'], Interfaces/UrlConstants::URL_REGEX_VALID);`
     */
    const URL_REGEX_VALID = '/^'.self::URL_REGEX_FRAG_SCHEME.self::URL_REGEX_FRAG_USER_PASS.self::URL_REGEX_FRAG_HOST_TLD_PORT.self::URL_REGEX_FRAG_URI_HASH.'$/u';
}
