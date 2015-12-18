<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Regex patterns.
 *
 * @since 150424 Initial release.
 */
class RegexPatterns extends Classes\AppBase
{
    /**
     * Search values containing regex patterns against a string.
     *
     * @since 150424 Initial release.
     *
     * @param string $string            String to search within.
     * @param mixed  $value             Any value can be converted into a regex pattern.
     *                                  Actually, objects can't, but this recurses into objects.
     * @param bool   $collect_key_props Collect array keys and/or object properties?
     *
     * @return bool|array Will return `TRUE` if any regex pattern finds a match.
     *                    If `$collect_key_props` is `TRUE`, this will return an array.
     */
    public function match(string $string, $value, bool $collect_key_props = false)
    {
        if (!isset($string[0])) { // Empty?
            return $collect_key_props ? [] : false;
        }
        $matching_key_props = []; // Initialize.

        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key_prop => $_value) {
                if (is_array($_value) || is_object($_value)) {
                    if (($_matching_key_props = $this->match($string, $_value, $collect_key_props))) {
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
            } // unset($_key_prop, $_value, $_matching_key_props);
        } elseif (!$collect_key_props && is_string($value) && isset($value[0])) {
            if (@preg_match($value, $string)) {
                return true;
            }
        }
        return $collect_key_props ? $matching_key_props : false;
    }
}
