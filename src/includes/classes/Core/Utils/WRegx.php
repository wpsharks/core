<?php
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
     * @param array|string $patterns        Watered-down regex patterns; array or line-delimited.
     * @param string       $exclusion_chars The behavior of `*` & `?`. Defaults to excluding `/`.
     * @param bool         $force_match_all Force `^$` into play & don't treat `^$` as special chars.
     * @param bool         $capture         Capture the matches, or use the default `(?:)` syntax?
     *
     * @note `$exclusion_chars` may not contain: `⁅`, `⒯`, `⁆`, `?` or `*`
     *
     * @return string A real regex pattern; ready for {@link preg_match()}.
     */
    public function __invoke($patterns, string $exclusion_chars = '/', bool $force_match_all = false, bool $capture = false): string
    {
        $regex = ''; // Initialize.

        if (is_string($patterns)) { // Convert to array.
            $patterns = preg_split('/['."\r\n".']+/u', $patterns, -1, PREG_SPLIT_NO_EMPTY);
        }
        if (!is_array($patterns)) { // Require an array now.
            throw new Exception('Invalid data type for patterns.');
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
     * @note `$exclusion_chars` may not contain: `⁅`, `⒯`, `⁆`, `?` or `*`
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
            throw new Exception('Missing `exclusion_chars`.');
        } elseif ($exclusion_chars !== '/') {
            $_exclusion_chars_array = $this->c::mbStrSplit($exclusion_chars);
            if (array_diff($_exclusion_chars_array, ['⁅', '⒯', '⁆', '?', '*']) !== $_exclusion_chars_array) {
                throw new Exception('Invalid `exclusion_chars`. May not exclude: `⁅`, `⒯`, `⁆`, `?` or `*`.');
            } // unset($_exclusion_chars_array); // Housekeeping.
        }
        $tokens          = []; // Initialize.
        $string          = $this->c::escRegex($string);
        $exclusion_chars = $this->c::escRegex($exclusion_chars);

        $string = preg_replace_callback('/\\\\\[((?:(?:[^[\]]+)|(?R))*)\\\\\]/u', function ($m) use (&$tokens) {
            $m[1] = mb_strpos($m[1], '\\!') === 0 ? '^'.mb_substr($m[1], 2) : $m[1];
            $m[1] = preg_replace('/([a-z0-9])\\\\\-([a-z0-9])/u', '${1}-${2}', $m[1]);
            $tokens[] = '['.$m[1].']'; // Save token.
            return '⁅⒯'.(count($tokens) - 1).'⒯⁆';
        }, $string); // Converts to character class.

        $string = preg_replace_callback('/\\\\\{((?:(?:[^{}]+)|(?R))*)\\\\\}/u', function ($m) {
            return '(?:'.str_replace(['\\{', '\\}', ','], ['(?:', ')', '|'], $m[1]).')';
        }, $string); // Converts to `(a|b|c)` alternation.

        if (!$force_match_all) { // Must come before `$exclusion_chars` below.
            // If not forcing `^$`, we need to treat them as special chars here.
            $string = preg_replace(['/\\\\\^/u', '/\\\\\$/u'], ['^', '$'], $string);
        }
        $string = preg_replace_callback('/(?:\\\\\?){3,}/u', function ($m) use ($exclusion_chars) {
            return '[^'.$exclusion_chars.']{'.strlen(str_replace('\\', '', $m[0])).'}';
        }, $string); // Becaues ??? (or more) should be treated like `?` instead of as a partial `??` operator.

        // Now convert `??` and `**` (or more) after having already converted `???`... above.
        $string = preg_replace(['/(?:\\\\\?){2}/u', '/(?:\\\\\*){2,}/u'], ['[\s\S]', '[\s\S]*?'], $string);

        // Now convert any remaining `?` and/or `*` operators after having dealt with `??` and `**` (or more) above.
        $string = preg_replace(['/\\\\\?/u', '/\\\\\*/u'], ['[^'.$exclusion_chars.']', '[^'.$exclusion_chars.']*?'], $string);

        foreach (array_reverse($tokens, true) as $_token => $_brackets) {
            // Must go in reverse order so nested tokens unfold properly.
            $string = str_replace('⁅⒯'.$_token.'⒯⁆', $_brackets, $string);
        } // unset($_token, $_brackets); // Housekeeping.

        return $force_match_all ? '^'.$string.'$' : $string;
    }

    /**
     * Bracket special chars.
     *
     * @since 160428 Watered-down regex.
     *
     * @param mixed $value Input value(s).
     *
     * @return string|array|object Bracketed value(s).
     */
    public function bracket($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->bracket($_value);
            } // unset($_key, $_value); // Housekeeping.
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Empty.
        }
        $tokens = []; // Initialize.

        $string = preg_replace_callback('/\[((?:(?:[^[\]]+)|(?R))*)\]/u', function ($m) use (&$tokens) {
            $tokens[] = '['.$m[1].']'; // Save token.
            return '⁅⒯'.(count($tokens) - 1).'⒯⁆';
        }, $string); // Tokenizes character class.

        $string = preg_replace('/[*?[\]!{},]/u', '[${0}]', $string);

        foreach (array_reverse($tokens, true) as $_token => $_brackets) {
            // Must go in reverse order so nested tokens unfold properly.
            $string = str_replace('⁅⒯'.$_token.'⒯⁆', $_brackets, $string);
        } // unset($_token, $_brackets); // Housekeeping.

        return $string;
    }

    /**
     * URL to WRegx URI pattern.
     *
     * @since 160428 Watered-down regex.
     *
     * @param string $url_uri Input URL (or URI).
     *
     * @return string WRegx URI pattern.
     */
    public function urlToUriPattern(string $url_uri)
    {
        if (!($parts = $this->c::parseUrl($url_uri))) {
            return ''; // Not possible.
        }
        $uri = $parts['uri'] ?? '';
        $uri = preg_split('/#/u', $uri, 2)[0];
        $uri = $this->c::mbTrim($uri, '/');

        if (!$uri) { // URI is empty now?
            return ''; // Nothing to do here.
        }
        // NOTE: This considers all possible `/endpoints` after the base.
        // Including the possibility of there being `index.php/path/info/`.

        // NOTE: If a URI contains multiple query string variables,
        // the best we can do is `{&**&,&,}` (searching in the order given).

        $uri_pattern = $this->bracket($uri); // i.e., `[?]`, etc.

        if (mb_strpos($uri_pattern, '[?]') !== false) {
            $uri_pattern = preg_replace_callback('/\[\?\]|&/u', function ($m) {
                return $m[0] === '[?]' ? '[?]{**&,}' : '{&**&,&,}';
            }, preg_replace('/([^\/])\[\?\]/u', '${1}{/**,}[?]', $uri_pattern));
            return $uri_pattern = '/'.$uri_pattern.'{&**,}';
        } else {
            return $uri_pattern = '/'.$uri_pattern.'{/**,}';
        }
    }
}
