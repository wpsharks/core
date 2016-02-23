<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Regex pattern utilities.
 *
 * @since 150424 Initial release.
 */
class RegexPattern extends Classes\Core
{
    /**
     * Match a regex pattern against other values.
     *
     * @since 150424 Initial release.
     *
     * @param string $regex             A regular expression.
     * @param mixed  $value             Any value can be converted into a string before comparison.
     *                                  Actually, objects can't, but this recurses into objects.
     * @param bool   $collect_key_props Collect array keys and/or object properties?
     *
     * @return bool|array Will return `true` if regex pattern finds a match.
     *                    If `$collect_key_props` is `true`; returns array.
     */
    public function in(string $regex, $value, bool $collect_key_props = false)
    {
        if (!$regex) { // Empty regex?
            return $collect_key_props ? [] : false;
        }
        $matching_key_props = []; // Initialize.

        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key_prop => $_value) {
                if (is_array($_value) || is_object($_value)) {
                    if (($_matching_key_props = $this->in($regex, $_value, $collect_key_props))) {
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
            } // unset($_key_prop, $_value, $_matching_key_props);
        } elseif (!$collect_key_props) {
            $value = (string) $value;
            if (preg_match($regex, $value)) {
                return true;
            }
        }
        return $collect_key_props ? $matching_key_props : false;
    }
}
