<?php
namespace WebSharks\Core\Traits;

/**
 * Regex Pattern Utilities.
 *
 * @since 150424 Initial release.
 */
trait RegexPatternUtils
{
    /**
     * Match a regex pattern against other values.
     *
     * @param string $regex             A regular expression.
     * @param mixed  $value             Any value can be converted into a string before comparison.
     *                                  Actually, objects can't, but this recurses into objects.
     * @param bool   $collect_key_props Collect array keys and/or object properties?
     *
     * @return bool|array Will return `TRUE` if regex pattern finds a match.
     *                    If `$collect_key_props` is `TRUE`, this will return an array.
     */
    protected function regexPatternIn($regex, $value, $collect_key_props = false)
    {
        if (!strlen($regex = (string) $regex)) {
            return $collect_key_props ? array() : false;
        }
        $matching_key_props = array(); // Initialize.

        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key_prop => $_value) {
                if (is_array($_value) || is_object($_value)) {
                    if (($_matching_key_props = $this->regexPatternIn($regex, $_value, $collect_key_props))) {
                        if ($collect_key_props) {
                            $matching_key_props[] = array($_key_prop => $_matching_key_props);
                        } else {
                            return true;
                        }
                    }
                } else {
                    $_value = (string) $_value;
                    if (preg_match($regex, $_value)) {
                        if ($collect_key_props) {
                            $matching_key_props[] = $_key_prop;
                        } else {
                            return true;
                        }
                    }
                }
            }
            unset($_key_prop, $_value, $_matching_key_props);
        } elseif (!$collect_key_props) {
            $value = (string) $value;
            if (preg_match($regex, $value)) {
                return true;
            }
        }
        return $collect_key_props ? $matching_key_props : false;
    }

    /**
     * Search values containing regex patterns against a string.
     *
     * @param string $string            String to search within.
     * @param mixed  $value             Any value can be converted into a regex pattern.
     *                                  Actually, objects can't, but this recurses into objects.
     * @param bool   $collect_key_props Collect array keys and/or object properties?
     *
     * @return bool|array Will return `TRUE` if any regex pattern finds a match.
     *                    If `$collect_key_props` is `TRUE`, this will return an array.
     */
    protected function inRegexPatterns($string, $value, $collect_key_props = false)
    {
        if (!strlen($string = (string) $string)) {
            return $collect_key_props ? array() : false;
        }
        $matching_key_props = array(); // Initialize.

        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key_prop => $_value) {
                if (is_array($_value) || is_object($_value)) {
                    if (($_matching_key_props = $this->inRegexPatterns($string, $_value, $collect_key_props))) {
                        if ($collect_key_props) {
                            $matching_key_props[] = array($_key_prop => $_matching_key_props);
                        } else {
                            return true;
                        }
                    }
                } elseif (is_string($_value) && isset($_value[0])) {
                    if (@preg_match($_value, $string)) {
                        if ($collect_key_props) {
                            $matching_key_props[] = $_key_prop;
                        } else {
                            return true;
                        }
                    }
                }
            }
            unset($_key_prop, $_value, $_matching_key_props);
        } elseif (!$collect_key_props && is_string($value) && isset($value[0])) {
            if (@preg_match($value, $string)) {
                return true;
            }
        }
        return $collect_key_props ? $matching_key_props : false;
    }
}
