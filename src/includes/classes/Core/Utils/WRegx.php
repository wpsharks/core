<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Watered-down regex.
 *
 * @since 150424 Adding watered-down regex.
 */
class WRegx extends Classes\Core\Base\Core
{
    /**
     * Convert watered-down regex to real regex.
     *
     * @since 150424 Adding watered-down regex parser.
     *
     * @param string $patterns        Watered-down regex patterns; line-delimited.
     * @param string $exclusion_char  The behavior of `*` & `?`. Defaults to excluding `/`.
     * @param bool   $force_match_all Force `^$` into play & don't treat `^$` as special chars.
     * @param bool   $capture         Capture the matches, or use the default `(?:)` syntax?
     *
     * @return string A real regex pattern; ready for {@link preg_match()}.
     */
    public function __invoke(string $patterns, string $exclusion_char = '/', bool $force_match_all = false, bool $capture = false): string
    {
        $regex = ''; // Initialize.

        $patterns            = preg_split('/['."\r\n".']+/u', $patterns, -1, PREG_SPLIT_NO_EMPTY);
        $patterns            = $this->c::removeEmptys($this->c::mbTrim($patterns));
        $regex_pattern_frags = $this->frag($patterns, $exclusion_char, $force_match_all);

        if ($regex_pattern_frags) { // Have an array of regex patterns frags?
            $regex = '/('.($capture ? '' : '?:').implode('|', $regex_pattern_frags).')/ui';
        }
        return $regex;
    }

    /**
     * To true regex fragment.
     *
     * @since 150424 Adding watered-down regex.
     *
     * @param mixed  $value           Input value(s) w/ watered-down regex.
     * @param string $exclusion_char  The behavior of `*` & `?`. Defaults to excluding `/`.
     * @param bool   $force_match_all Force `^$` into play & don't treat `^$` as special chars.
     *
     * @return string|array|object Value w/ true regex fragments.
     */
    public function frag($value, string $exclusion_char = '/', bool $force_match_all = false)
    {
        if (!$exclusion_char) { // Must have this.
            throw new Exception('Missing `exclusion_char`.');
        }
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->frag($_value, $exclusion_char);
            } // unset($_key, $_value); // Housekeeping.

            return $value;
        }
        $string = (string) $value;

        if (!isset($string[0])) {
            return $string;
        }
        $tokens         = []; // Initialize.
        $string         = $this->c::escRegex($string);
        $exclusion_char = $this->c::escRegex($exclusion_char, '/');

        $string = preg_replace_callback('/\\\\\[((?:(?:[^[\]]+)|(?R))*)\\\\\]/u', function ($m) use (&$tokens) {
            $m[1] = mb_strpos($m[1], '\\!') === 0 ? '^'.mb_substr($m[1], 2) : $m[1];
            $m[1] = preg_replace('/([a-z0-9])\\\\\-([a-z0-9])/u', '${1}-${2}', $m[1]);
            $tokens[] = '['.$m[1].']'; // Save token.
            return '|%#%|'.(count($tokens) - 1).'|%#%|';
        }, $string); // Converts to character class.

        $string = preg_replace_callback('/\\\\\{((?:(?:[^{}]+)|(?R))*)\\\\\}/u', function ($m) {
            return '(?:'.str_replace(['\\{', '\\}', ','], ['(?:', ')', '|'], $m[1]).')';
        }, $string); // Converts to character class.

        if (!$force_match_all) { // Must come before `$exclusion_char` below.
            // If not forcing `^$`, we need to treat them as special chars here.
            $string = preg_replace(['/\\\\\^/u', '/\\\\\$/u'], ['^', '$'], $string);
        }
        $string = preg_replace(['/(?:\\\\\?){2,}/u', '/(?:\\\\\*){2,}/u'], ['[\s\S]', '[\s\S]*?'], $string);
        $string = preg_replace(['/\\\\\?/u', '/\\\\\*/u'], ['[^'.$exclusion_char.']', '[^'.$exclusion_char.']*?'], $string);

        foreach (array_reverse($tokens, true) as $_token => $_brackets) {
            // Must go in reverse order so nested tokens unfold properly.
            $string = str_replace('|%#%|'.$_token.'|%#%|', $_brackets, $string);
        } // unset($_token, $_brackets); // Housekeeping.

        return $force_match_all ? '^'.$string.'$' : $string;
    }
}
