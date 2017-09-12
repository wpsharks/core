<?php
/**
 * Simple expression-related constants.
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
 * Simple expression-related constants.
 *
 * @since 160708 Initial release.
 */
interface SimpleExpressionConstants
{
    /**
     * A simple expression.
     *
     * @since 160708 Expression regex patterns.
     *
     * @var string Regex fragment for use in `preg_match()`.
     */
    const SIMPLE_EXPRESSION_REGEX_FRAG = // Begin regex.

        '(?i:'.// CaSe-insensitive matching (inline modifier).
            '(?<brackets>'.// Sub-routine for recursion.

                '\s*\(\s*'.// An opening round bracket.
                // Any amount of whitespace before/after bracket.

                    '(?:'.// Begin expression loop.

                        // A test-fragment; i.e., not a logical-operator or comparison-operator.
                        '(?!(?:AND|OR|&&|\|\||\=\=\=|\!\=\=|\=\=|\!\=|\<\=|\>\=|\<\>|\>|\<)[\s()])[^\s()]+'.

                            '(?:'.// Followed by a comparison-operator that is followed by a comparison-value.
                                '\s+(?:\=\=\=|\!\=\=|\=\=|\!\=|\<\=|\>\=|\<\>|\>|\<)'.// e.g., ` == comparison-value`.
                                '\s+(?!(?:AND|OR|&&|\|\||\=\=\=|\!\=\=|\=\=|\!\=|\<\=|\>\=|\<\>|\>|\<)[\s()])[^\s()]+'.
                            ')?'.// This comparison is optional. If not found, treat it as a boolean condition.

                            '(?:'. // Followed by an upcoming `)`. Or, followed by a logical-operator.
                                '\s*(?=[)])'. // e.g., `(a)`, `(a AND b)`, `(a OR b)` ... upcoming `)`.
                                // Or, a logical-operator; not before an upcoming `)`, or before an upcoming `(`.
                                '|\s+(?:AND|OR|&&|\|\|)(?:\s+(?![)])|\s*(?=[(]))'.// e.g., ` AND `, ` AND(`.
                            ')'.

                        '|(?&brackets)'. // Or `<brackets>` (nested recursion via sub-routine).
                            '(?='. // Followed by one of these; allowing a jump to another set of brackets.
                                '(?:'.
                                    '\s*[)]'. // Followed by an upcoming `)` bracket that closes the expression.
                                    '|\s*(?:AND|OR|&&|\|\|)'. // Or, followed by a logical-operator.
                                        '(?='. // Where the logical-operator is followed by one of these.
                                            '\s*[(]'. // Followed by another set of brackets; e.g., `)AND(`, `) OR (`.
                                            // Or, followed by a test-fragment that gets picked up in the next iteration.
                                            '|\s+(?!(?:AND|OR|&&|\|\||\=\=\=|\!\=\=|\=\=|\!\=|\<\=|\>\=|\<\>|\>|\<)[\s()])'.
                                        ')'.
                                ')'.
                            ')'. // This, coupled with lookahead, allows a jump to another set of brackets.
                            // Also allows a jump from brackets to a new token that's not in brackets; e.g., `(a) OR a`.
                            '(?:\s*(?:AND|OR|&&|\|\|)\s*)?'. // Eat logical-operator before starting next iteration.

                    ')+'.// End expression loop.

                '\s*\)\s*'.// A closing round bracket.
                // Any amount of whitespace before/after bracket.

            ')'.// End capture group used for recursion.
        ')';// End caSe-insensitive matching (inline modifier).

    /**
     * A valid simple expression.
     *
     * @since 160708 Expression regex patterns.
     *
     * @var string Regex fragment for use in `preg_match()`.
     */
    const SIMPLE_EXPRESSION_REGEX_VALID = '/^'.self::SIMPLE_EXPRESSION_REGEX_FRAG.'$/u';
}
