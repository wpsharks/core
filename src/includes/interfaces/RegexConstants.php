<?php
declare (strict_types = 1);
namespace WebSharks\Core\Interfaces;

/**
 * Regex-related constants.
 *
 * @since 150424 Initial release.
 */
interface RegexConstants
{
    /**
     * Not ASCII or invisible.
     *
     * @since 150424 Initial release.
     *
     * @type string Regex fragment for use in `preg_match()`.
     *
     * @see <http://www.regular-expressions.info/unicode.html>
     */
    const REGEX_FRAG_NOT_ASCII_OR_INVISIBLE = '[^\x{00}-\x{7F}\p{Z}\p{C}]';

    /**
     * Finds a double quoted value.
     *
     * @since 150424 Initial release.
     *
     * @type string Regex fragment for use in `preg_match()`.
     */
    const REGEX_FRAG_DQ_VALUE = // Tricky tricky!
        '(?<open_dq>(?<!\\\\)")'.// A double quote that has not been escaped.
            '(?<dq_value>(?s:\\\\.|[^\\\\"])*?)'.// Any escaped character (allowing for `\"`).
            //          | Or, anything that is not a `\` or `"` character.
        '(?<close_dq>")'; // Close double quote.

    /**
     * Finds a single quoted value.
     *
     * @since 150424 Initial release.
     *
     * @type string Regex fragment for use in `preg_match()`.
     */
    const REGEX_FRAG_SQ_VALUE = // Tricky tricky!
        '(?<open_sq>(?<!\\\\)\')'.// A single quote that has not been escaped.
            '(?<sq_value>(?s:\\\\.|[^\\\\\'])*?)'.// Any escaped character (allowing for `\'`).
            //          | Or, anything that is not a `\` or `'` character.
        '(?<close_sq>\')'; // Close single quote.

    /**
     * Finds a single or double quoted value.
     *
     * @since 150424 Initial release.
     *
     * @type string Regex fragment for use in `preg_match()`.
     */
    const REGEX_FRAG_DSQ_VALUE = // Super duper tricky!
        '(?<open_dsq>(?<!\\\\)["\'])'.// A quote that has not been escaped.
            '(?<dsq_value>(?s:\\\\.|(?!\\\\|(?P=open_dsq)).)*?)'.// Any escaped character (allowing for `\"`, `\'`).
            //          | Or, anything that is not a `\` or `'"` character.
        '(?<close_dsq>(?P=open_dsq))'; // Close quote.
}
