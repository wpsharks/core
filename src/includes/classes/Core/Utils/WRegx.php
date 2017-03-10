<?php
/**
 * Watered-down regex.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Watered-down regex.
 *
 * @since 150424 Watered-down regex.
 */
class WRegx extends Classes\Core\Base\Core
{
    /**
     * Convert watered-down regex to real regex.
     *
     * @since 150424 Watered-down regex.
     *
     * - `*` Matches zero or more characters that are not a `/`
     * - `**` Matches zero or more characters of any kind.
     * - `?` Matches exactly one character that is not a `/`
     * - `??` Matches exactly one character of any kind.
     * - `[abc]` Matches exactly one character: `a`, `b`, or `c`.
     * - `[a-z0-9]` Matches exactly one character: `a` thru `z` or `0` thru `9`.
     * - `[!abc]` A leading `!` inside `[]` negates; i.e., anything that is not: `a`, `b`, or `c`.
     * - `{abc,def}` Matches the fragment `abc` or `def` (one or the other).
     * - `{abc,def,}` Matches `abc`, `def` or nothing; i.e., an optional match.
     * - `{/**,}` Matches a `/` followed by zero or more characters. Or nothing.
     * - `[*?[]!{},^$]` Matches a literal special character. One of: `*?[]!{},^$` explicitly.
     *
     * - `^` = beginning of the string (but can be turned off depending on the use case).
     * - `$` = end of the string (but can be turned off depending on the use case).
     *
     * @param array|string $patterns        Watered-down regex patterns; array or line-delimited.
     * @param string       $exclusion_chars The behavior of `*` & `?`. Defaults to excluding `/`.
     * @param bool         $force_match_all Force `^$` into play & don't treat `^$` as special chars.
     * @param bool         $capture         Capture the matches, or use the default `(?:)` syntax?
     *
     * @internal `$exclusion_chars` may not contain: `⁅`, `⒯`, `⁆`, `?` or `*`
     *
     * @return string A real regex pattern; ready for {@link preg_match()}.
     */
    public function __invoke($patterns, string $exclusion_chars = '/', bool $force_match_all = false, bool $capture = false): string
    {
        $regex = ''; // Initialize.

        if (is_string($patterns)) { // Convert to array.
            $patterns = preg_split('/\v+/u', $patterns, -1, PREG_SPLIT_NO_EMPTY);
        }
        if (!is_array($patterns)) { // Require an array now.
            throw $this->c::issue('Invalid data type for patterns.');
        }
        $patterns            = $this->c::remove0Bytes($patterns);
        $regex_pattern_frags = $this->frag($patterns, $exclusion_chars, $force_match_all);

        if ($regex_pattern_frags) { // Have an array of regex pattern fragments?
            $regex = '/('.($capture ? '' : '?:').implode('|', $regex_pattern_frags).')/u';
        }
        return $regex;
    }

    /**
     * Convert watered-down regex to real regex fragment.
     *
     * @since 150424 Watered-down regex.
     *
     * @param mixed  $value           Input value(s) w/ watered-down regex.
     * @param string $exclusion_chars The behavior of `*` & `?`. Defaults to excluding `/`.
     * @param bool   $force_match_all Force `^$` into play & don't treat `^$` as special chars.
     *
     * @internal `$exclusion_chars` may not contain: `⁅`, `⒯`, `⁆`, `?` or `*`
     *
     * @return string|array|object Value(s) as true regex fragments.
     */
    public function frag($value, string $exclusion_chars = '/', bool $force_match_all = false)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->frag($_value, $exclusion_chars, $force_match_all);
            } // unset($_key, $_value); // Housekeeping.
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Empty.
        }
        if (!$exclusion_chars) { // Must have this.
            throw $this->c::issue('Missing `exclusion_chars`.');
        } elseif ($exclusion_chars !== '/') {
            $_exclusion_chars_array = $this->c::mbStrSplit($exclusion_chars);
            if (array_diff($_exclusion_chars_array, ['⁅', '⒯', '⁆', '?', '*']) !== $_exclusion_chars_array) {
                throw $this->c::issue('Invalid `exclusion_chars`. May not exclude: `⁅`, `⒯`, `⁆`, `?` or `*`.');
            } // unset($_exclusion_chars_array); // Housekeeping.
        }
        $tokens          = []; // Initialize.
        $string          = $this->c::escRegex($string);
        $exclusion_chars = $this->c::escRegex($exclusion_chars);

        // Convert `[!a-z0-9]` into a real regex `[^character class]`.
        // Special characters inside a character class have no further meaning.
        // For this reason, we remove them from the equation by tokenizing them, for now.
        $string = preg_replace_callback('/\\\\\[((?:(?:[^[\]]+)|(?R))*)\\\\\]/u', function ($m) use (&$tokens) {
            $m[1] = mb_strpos($m[1], '\\!') === 0 ? '^'.mb_substr($m[1], 2) : $m[1];
            $m[1] = preg_replace('/([a-z0-9])\\\\\-([a-z0-9])/u', '${1}-${2}', $m[1]);
            $tokens[] = '['.$m[1].']';
            return '⁅⒯'.(count($tokens) - 1).'⒯⁆';
        }, $string); // See restoration below.

        // Convert `{}` into `(?:this|that)`; i.e., a list of alternates.
        $string = preg_replace_callback('/\\\\\{((?:(?:[^{}]+)|(?R))*)\\\\\}/u', function ($m) {
            return '(?:'.str_replace(['\\{', '\\}', ','], ['(?:', ')', '|'], $m[1]).')';
        }, $string); // `,` becomes `|` for regex alternation.

        // Deal with `^` and `$`, depending on `$force_match_all`.
        if (!$force_match_all) { // Must come before `$exclusion_chars` below.
            // If not forcing `^$`, we need to treat them as special chars here.
            $string = preg_replace(['/\\\\\^/u', '/\\\\\$/u'], ['^', '$'], $string);
        }
        // `???` (or more) should be treated like `?` instead of as a partial `??` operator.
        $string = preg_replace_callback('/(?:\\\\\?){3,}/u', function ($m) use ($exclusion_chars) {
            return '[^'.$exclusion_chars.']{'.strlen(str_replace('\\', '', $m[0])).'}';
        }, $string); // i.e., Treat as three or more instances of `?` by itself.

        // Now convert `??` and `**` (or more) after having already converted `???`... above.
        $string = preg_replace(['/(?:\\\\\?){2}/u', '/(?:\\\\\*){2,}/u'], ['[\s\S]', '[\s\S]*?'], $string);

        // Now convert any remaining `?` and/or `*` operators after having dealt with `??` and `**` (or more) above.
        $string = preg_replace(['/\\\\\?/u', '/\\\\\*/u'], ['[^'.$exclusion_chars.']', '[^'.$exclusion_chars.']*?'], $string);

        // Restore special characters tokenized when we began above.
        foreach (array_reverse($tokens, true) as $_token => $_brackets) {
            // Must go in reverse order so nested tokens unfold properly.
            $string = str_replace('⁅⒯'.$_token.'⒯⁆', $_brackets, $string);
        } // unset($_token, $_brackets); // Housekeeping.

        // Return value based on `$force_match_all`.
        return $force_match_all ? '^'.$string.'$' : $string;
    }

    /**
     * Bracket special chars.
     *
     * @since 170124.74961 New name. Old name `bracket()`.
     * @since 170124.74961 Adding `$will_force_match_all` param.
     *
     * @param mixed $value                Input value(s) to bracket here.
     * @param bool  $will_force_match_all If true, don't treat `^$` as special chars.
     *
     * @return string|array|object Bracketed value(s).
     */
    public function bracketSpecialChars($value, bool $will_force_match_all = false)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->bracketSpecialChars($_value, $will_force_match_all);
            } // unset($_key, $_value); // Housekeeping.
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Empty.
        }
        if (!$will_force_match_all) {
            $string = preg_replace('/[*?[\]!{},\^$]/u', '[${0}]', $string);
        } else { // `^` and `$` are not special characters.
            $string = preg_replace('/[*?[\]!{},]/u', '[${0}]', $string);
        }
        return $string;
    }

    /**
     * URL to WRegx URI pattern.
     *
     * @since 160428 Watered-down regex.
     * @since 170124.74961 Adding `$will_force_match_all` param.
     * @since 170124.74961 Fixing bug in query string wildcard pattern.
     *
     * @param mixed $value                Input value(s) containing:
     *                                    URLs, URIs, or query strings w/ a leading `?`.
     * @param bool  $will_force_match_all If true, don't treat `^$` as special chars.
     *
     * @return string WRegx URI pattern, else an empty string if unable to parse.
     *
     * - This considers all possible `/endpoints` after the base.
     *   Including the possibility of there being `index.php/path/info/`.
     *
     * - If a URI contains multiple query string variables,
     *   the best we can do is `{&**&,&}` (searching in the order given).
     *
     * - In the case of a root URI or empty path, this returns a pattern
     *   matching any path [/endpoints] & query string; i.e., matches all URIs.
     *   This will also be true if only a query string is given. Path is empty.
     */
    public function urlToUriPattern($value, bool $will_force_match_all = false)
    {
        // Note: We must allow for `0` here.
        // It will parse as `[path => '0']`, which is valid.

        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->urlToUriPattern($_value, $will_force_match_all);
            } // unset($_key, $_value); // Housekeeping.
            return $value;
        }
        $url_uri_qsl = (string) $value; // Force string.

        if (!isset($url_uri_qsl[0])) {
            return ''; // Not possible.
        } elseif (!($parts = $this->c::parseUrl($url_uri_qsl))) {
            return ''; // Not possible.
        }
        $uri_no_fragment     = $parts['uri_no_fragment'] ?? '';
        $uri_no_fragment_lts = $uri_no_fragment ? $this->c::mbTrim($uri_no_fragment, '/') : $uri_no_fragment;
        $uri_pattern         = $uri_no_fragment_lts ? $this->bracketSpecialChars($uri_no_fragment_lts, $will_force_match_all) : $uri_no_fragment_lts;

        if (!isset($uri_pattern[0])) { // Treat as root URI.
            return '{/**,}'; // Any path [/endpoints] & query string.
            //
        } elseif (mb_strpos($uri_pattern, '[?]') !== false) {
            $uri_pattern        = preg_replace('/(^|[^\/])\[\?\]/u', '${1}{/**,}[?]', $uri_pattern);
            $uri_pattern        = preg_replace('/&/u', '{&**&,&}', $this->c::mbRTrim($uri_pattern, '&'));
            $uri_pattern        = preg_replace('/\[\?\]/u', '[?]{**&,}', $uri_pattern); // After `&` replacements.

            if (mb_strpos($uri_pattern, '{/**,}') === 0) {
                return $uri_pattern = $uri_pattern.'{&**,}'; // Any path [/endpoints] w/ the query string.
            } else {
                return $uri_pattern = '/'.$uri_pattern.'{&**,}'; // Specific path [/endpoints] w/ the query string.
            }
        } else {
            return $uri_pattern = '/'.$uri_pattern.'{/**,}'; // Specific path [/endpoints] & query string.
        }
    }
}
