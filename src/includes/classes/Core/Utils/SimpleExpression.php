<?php
/**
 * Simple expression utils.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Simple expression utils.
 *
 * @since 160708 Simple expression utils.
 */
class SimpleExpression extends Classes\Core\Base\Core implements Interfaces\SimpleExpressionConstants
{
    /**
     * Builds a PHP expression from a simple expression string.
     *
     * @since 160708 Simple expression utils.
     *
     * @param string   $expr     A simple expression.
     * @param callable $callback A callback template handler.
     *
     * @internal The `$callback` receives one argument, a raw test-fragment (string, not quoted).
     *  The callback should always return a single PHP function call (as a string), using the test-fragment.
     *  e.g., `'check_something("'".$test_fragment."'")'`, `'get_something("'".$test_fragment."'")'`, etc.
     *
     * @return string PHP expression from a simple expression string.
     *
     * @internal The following reserved characters should NOT be used as a part of any test-fragment or comparison-value.
     *  Reserved chars are: `(`, `)`, and all whitespace. Brackets & whitespace are delimiters in the expression.
     *
     * @internal Another special char is `!`, which can appear before any test-fragment to negate the test.
     *  This is not considered a reserved char though, because it is only valid as the first char in a test-fragment.
     *  Also, it is only valid when the test-fragment contains a total of 2+ chars (counting the `!` symbol itself).
     *  It's also worth noting that the `!` symbol is not passed to the `$callback` when it's a valid negation.
     *
     * @internal There are also reserved sequences that form logical-operators and/or comparison-operators that should NOT be
     *  used as any stand-alone test-fragment or comparison-value. They can be part of a test-fragment or comparison-value,
     *  but not stand-alone; i.e., any of these preceded by `^|[\s()]` and followed by `[\s()]|$` form an operator.
     *  Reserved sequences include: `AND`, `OR`, `&&`, `||`, `===`, `!==`, `==`, `!=`, `<=`, `>=`, `<>`, `>`, `<`.
     *
     * @internal Other special chars can be implemented by the caller as a part of the test-fragment.
     *  For instance, it might be desirable to allow for a test-fragment that starts with a function name.
     *  e.g., `function:arg`, `function:arg,arg`, etc. So long as it doesn't use reserved chars you're fine.
     *  Just be sure to document special chars. In this example, `[:,]` would be reserved in test-fragments.
     */
    public function toPhp(string $expr, callable $callback): string
    {
        if (!$expr) { // Expression empty?
            return ''; // Nothing to do in this case.
        } elseif (!preg_match($this::SIMPLE_EXPRESSION_REGEX_VALID, '('.$expr.')')) {
            return ''; // Not possible; i.e., not a valid simple expression.
            // NOTE: This validation is important. If it fails, the sub-routines below could
            // produce a PHP expression that contains syntax errors. Or worse, a security exploit.
            // In short, this validation allows us to bypass expensive, individual, token-based validation.
            // Once this validation passes, sub-routines below make several assumptions about the expression.
        }
        $delimiters_regex     = '/([()])|\s+/u';
        $logical_operators    = ['AND', 'OR', '&&', '||'];
        $comparison_operators = ['===', '!==', '==', '!=', '<=', '>=', '<>', '>', '<'];

        $conditions      = ''; // Initialize conditions.
        $_previous_token = $_previous_token_type = ''; // Initialize previous token.
        $_tokens         = preg_split($delimiters_regex, $expr, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        for ($_i = 0; $_i < count($_tokens); ++$_i) {
            $_token = $_tokens[$_i]; // Current token.

            if ($_token === '(' || $_token === ')') {
                $_space = $_token === '(' && $_previous_token_type === 'logical-operator' ? ' ' : '';
                $conditions .= $_space.$_token;
                $_previous_token      = $_token;
                $_previous_token_type = 'bracket';
                //
            } elseif (in_array(mb_strtoupper($_token), $logical_operators, true)) {
                $_space = ' '; // Always.
                $conditions .= $_space.$_token;
                $_previous_token      = $_token;
                $_previous_token_type = 'logical-operator';
                //
            } else { // Append a new condition.
                $_space = $conditions !== '' && $_previous_token !== '(' ? ' ' : '';

                if ($_token[0] === '!' && isset($_token[1])) {
                    $_negating      = '!'; // Negating.
                    $_test_fragment = mb_substr($_token, 1);
                } else {
                    $_negating      = ''; // Not negating.
                    $_test_fragment = $_token;
                }
                if (isset($_tokens[$_i + 1], $_tokens[$_i + 2]) && in_array($_tokens[$_i + 1], $comparison_operators, true)) {
                    $_comparison_operator = $_tokens[$_i + 1]; // e.g., `===`, `!==`, `>=`, `>`, etc.
                    $_comparison_value    = $_tokens[$_i + 2]; // i.e., A value to compare.

                    if (isset($_comparison_value[0]) && is_numeric($_comparison_value)) {
                        // NOTE: To test for a numeric string literal, wrap it in quotes; e.g., '123', '45.06', etc.
                    } elseif ($_comparison_value && in_array(mb_strtolower($_comparison_value), ['true', 'false', 'null'], true)) {
                        // NOTE: To test for a string literal, wrap it in quotes; e.g., 'true', 'false', 'null'.
                    } else { // Anything else becomes a quoted string literal.
                        $_comparison_value = $this->quoteStrLiteral($_comparison_value);
                    }
                    if ($_negating) { // If negating, must wrap the expression in brackets.
                        $conditions .= $_space.$_negating.'('.$callback($_test_fragment).' '.$_comparison_operator.' '.$_comparison_value.')';
                    } else {
                        $conditions .= $_space.$callback($_test_fragment).' '.$_comparison_operator.' '.$_comparison_value;
                    }
                    $_i                   = $_i + 2;
                    $_previous_token      = $_token;
                    $_previous_token_type = 'test-fragment+comparison';
                    //
                } else { // If no operator, use a boolean test only.
                    $conditions .= $_space.$_negating.$callback($_test_fragment);
                    $_previous_token      = $_token;
                    $_previous_token_type = 'test-fragment';
                }
            }
        } // unset($_tokens, $_token, $_previous_token, $_previous_token_type);
        // unset($_space, $_negating, $_test_fragment, $_comparison_operator, $_comparison_value);

        return $conditions; // NOTE: Caller should wrap this in brackets if it's being concatenated with others.
    }

    /**
     * Quote string literal.
     *
     * @since 160708 Simple expression utils.
     *
     * @param string $string String to quote.
     *
     * @return string Quoted string literal. If empty, returns `''`.
     *
     * @internal This trims existing 'single' and/or "double" quoted encapsulations before quoting again.
     * However, for that reason, escaped quotes inside an already-quoted string are always escaped again.
     * So if you're going to quote a string literal in a simple expression, you should not escape inner quotes.
     *
     * @internal The simple expression syntax does NOT allow for spaces (or brackets) in any individual token.
     *  i.e., It is NOT possible to quote a string containing a space (or brackets) at this time.
     */
    protected function quoteStrLiteral(string $string): string
    {
        if (!isset($string[0])) {
            return "''"; // Empty string.
        } elseif ($string === "''" || $string === '""') {
            return "''"; // Empty string.
        } elseif (mb_strpos($string, "'") === 0 && mb_substr($string, -1) === "'") {
            return "'".str_replace("'", "\\'", mb_substr($string, 1, -1))."'";
        } elseif (mb_strpos($string, '"') === 0 && mb_substr($string, -1) === '"') {
            return "'".str_replace("'", "\\'", mb_substr($string, 1, -1))."'";
        } else {
            return "'".str_replace("'", "\\'", $string)."'";
        }
    }
}
