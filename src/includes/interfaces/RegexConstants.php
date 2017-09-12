<?php
/**
 * Regex-related constants.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Interfaces;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
#
use function assert as debug;
use function get_defined_vars as vars;

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
     * @var string Regex fragment for use in `preg_match()`.
     *
     * @link http://www.regular-expressions.info/unicode.html
     */
    const REGEX_FRAG_NOT_ASCII_OR_INVISIBLE = '[^\x{00}-\x{7F}\p{Z}\p{C}]';

    /**
     * Finds a double quoted value.
     *
     * @since 150424 Initial release.
     *
     * @var string Regex fragment for use in `preg_match()`.
     */
    const REGEX_FRAG_DQ_VALUE =
        '(?<open_dq>(?<!\\\\)")'.// A double quote that has not been escaped.
            '(?<dq_value>(?s:\\\\.|[^\\\\"])*?)'.// Any escaped character (allowing for `\"`).
            //          | Or, anything that is not a `\` or `"` character.
        '(?<close_dq>")'; // Close double quote.

    /**
     * Finds a single quoted value.
     *
     * @since 150424 Initial release.
     *
     * @var string Regex fragment for use in `preg_match()`.
     */
    const REGEX_FRAG_SQ_VALUE =
        '(?<open_sq>(?<!\\\\)\')'.// A single quote that has not been escaped.
            '(?<sq_value>(?s:\\\\.|[^\\\\\'])*?)'.// Any escaped character (allowing for `\'`).
            //          | Or, anything that is not a `\` or `'` character.
        '(?<close_sq>\')'; // Close single quote.

    /**
     * Finds a single or double quoted value.
     *
     * @since 150424 Initial release.
     *
     * @var string Regex fragment for use in `preg_match()`.
     */
    const REGEX_FRAG_DSQ_VALUE =
        '(?<open_dsq>(?<!\\\\)["\'])'.// A quote that has not been escaped.
            '(?<dsq_value>(?s:\\\\.|(?!\\\\|(?P=open_dsq)).)*?)'.// Any escaped character (allowing for `\"`, `\'`).
            //          | Or, anything that is not a `\` or `'"` character.
        '(?<close_dsq>(?P=open_dsq))'; // Close quote.
}
