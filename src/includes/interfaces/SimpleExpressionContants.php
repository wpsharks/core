<?php
declare (strict_types = 1);
namespace WebSharks\Core\Interfaces;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Simple expression-related constants.
 *
 * @since 160708 Initial release.
 */
interface SimpleExpressionConstants
{
    /**
     * A simple expression fragment.
     *
     * @since 160708 Expression regex patterns.
     *
     * @type string Regex fragment for use in `preg_match()`.
     */
    const SIMPLE_EXPRESSION_REGEX_FRAG =
        '(?i:'.// Enable caSe-insensitive matching.
            '(?<brackets>'.// Sub-routine for recursion.

                '\s*\(\s*'.// An opening round bracket.
                // Any amount of whitespace before/after bracket.

                    // First thing inside a bracket can't be a logical-operator or comparison-operator.
                    '(?!(?:AND|OR|&&|\|\|\=\=\=|\!\=\=|\=\=|\!\=|\<\=|\>\=|\<\>|\>|\<)[\s()])'.
                    // i.e., Disallow `(AND`, `(OR`, `(!==`, `(===`, etc.

                    '(?:'.// Begin expression loop.

                        // A test-fragment; i.e., not a logical-operator or comparison-operator.
                        '(?!(?:AND|OR|&&|\|\|\=\=\=|\!\=\=|\=\=|\!\=|\<\=|\>\=|\<\>|\>|\<)[\s()])[^\s()]+'.
                            '(?:'.// Followed by a comparison-operator that is followed by a comparison-value.
                                '\s+(?:\=\=\=|\!\=\=|\=\=|\!\=|\<\=|\>\=|\<\>|\>|\<)'.// e.g., ... ` === comparison-value`.
                                '\s+(?!(?:AND|OR|&&|\|\|\=\=\=|\!\=\=|\=\=|\!\=|\<\=|\>\=|\<\>|\>|\<)[\s()])[^\s()]+'.
                            ')?'.// This comparison is optional. If not found, treat it as a boolean conditional.

                            '(?:'.// Followed by a logical-operator that's not before an upcoming closing bracket.
                                '\s+(?:AND|OR|&&|\|\|)\s+(?![)])'.// e.g., ... ` AND `.

                                // Or, any whitespace followed by an upcoming closing bracket.
                                '|\s*(?=[)])'.// e.g., This allows for `(a)` all by itself, or `(a === b)` by itself.
                                // Also allows the last test inside brackets to be valid; e.g., ... `test-fragment === comparison-value)`.
                                // i.e., it allows for nothing (`\s*`) before an upcoming closing bracket.
                            ')'.
                        // Or, `<brackets>` sub-routine recursion (nested brackets).
                        '|(?&brackets)(?=(?:\s*(?:\)|$)|\s*(?:AND|OR|&&|\|\|)\s*(?=[(])))'.
                        // Allow another nested set of brackets followed by a closing `)` bracket or end of string.
                        // Also allow another nested set of brackets followed by a logical-operator; e.g., `) OR (`;
                        //  which allows the loop to jump from one set of brackets to another when it is separated by a logical-operator.
                            '(?:\s*(?:AND|OR|&&|\|\|)\s*(?=[(]))?'. // Picks up a logical-operator between two bracketed expressions; e.g., `) OR (`.

                    ')+'.// End expression loop.

                '\s*\)\s*'.// A closing round bracket.
                // Any amount of whitespace before/after bracket.

            ')'.// End capture group used for recursion.
        ')';// End caSe-insensitive matching.

    /**
     * A simple, bool-only expression fragment.
     *
     * @since 160708 Expression regex patterns.
     *
     * @type string Regex fragment for use in `preg_match()`.
     */
    const SIMPLE_EXPRESSION_BOOL_ONLY_REGEX_FRAG =
        '(?i:'.// Enable caSe-insensitive matching.
            '(?<brackets>'.// Sub-routine for recursion.

                '\s*\(\s*'.// An opening round bracket.
                // Any amount of whitespace before/after bracket.

                    // The first thing inside a bracket cannot be a logical operator.
                    '(?!(?:AND|OR|&&|\|\|)[\s()])'.// i.e., Disallow `(AND`, `(OR`, etc.

                    '(?:'.// Begin expression loop.

                        // A test-fragment; i.e., not a logical-operator.
                        '(?!(?:AND|OR|&&|\|\|)[\s()])[^\s()]+'.// e.g., `something-to-test-for`.
                            '(?:'.// Followed by a logical-operator that is not before an upcoming closing bracket.
                                '\s+(?:AND|OR|&&|\|\|)\s+(?![)])'.// e.g., ... ` AND `.

                                // Or, any whitespace followed by an upcoming closing bracket.
                                '|\s*(?=[)])'.// e.g., This allows for `(a)` all by itself.
                                // Also allows the last test inside brackets to be valid; e.g., ... `test-fragment)`.
                                // i.e., it allows for nothing (`\s*`) before an upcoming closing bracket.
                            ')'.
                        // Or, `<brackets>` sub-routine recursion (nested brackets).
                        '|(?&brackets)(?=(?:\s*(?:\)|$)|\s*(?:AND|OR|&&|\|\|)\s*(?=[(])))'.
                        // Allow another nested set of brackets followed by a closing `)` bracket or end of string.
                        // Also allow another nested set of brackets followed by a logical-operator; e.g., `) OR (`;
                        //  which allows the loop to jump from one set of brackets to another when it is separated by a logical-operator.
                            '(?:\s*(?:AND|OR|&&|\|\|)\s*(?=[(]))?'. // Picks up a logical-operator between two bracketed expressions; e.g., `) OR (`.

                    ')+'.// End expression loop.

                '\s*\)\s*'.// A closing round bracket.
                // Any amount of whitespace before/after bracket.

            ')'.// End capture group used for recursion.
        ')';// End caSe-insensitive matching.

    /**
     * A valid simple expression.
     *
     * @since 160708 Expression regex patterns.
     *
     * @type string Regex fragment for use in `preg_match()`.
     */
    const SIMPLE_EXPRESSION_REGEX_VALID = '/^'.self::SIMPLE_EXPRESSION_REGEX_FRAG.'$/u';

    /**
     * A valid simple, bool-only expression.
     *
     * @since 160708 Expression regex patterns.
     *
     * @type string Regex fragment for use in `preg_match()`.
     */
    const SIMPLE_EXPRESSION_BOOL_ONLY_REGEX_VALID = '/^'.self::SIMPLE_EXPRESSION_BOOL_ONLY_REGEX_FRAG.'$/u';
}
