<?php
namespace WebSharks\Core\Classes;

/**
 * Wildcard pattern.
 *
 * @since 150424 Initial release.
 */
class WildcardPattern extends AbsBase
{
    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Match a wildcard pattern against other scalar values.
     *
     * @since 150424 Initial release.
     *
     * @param string   $wildcard          A wildcard pattern to look for.
     * @param mixed    $value             Any value can be converted into a string before comparison.
     *                                    Actually, objects can't, but this recurses into objects.
     * @param bool     $caSe_insensitive  If `TRUE`, enables the `FNM_CASEFOLD` flag.
     * @param bool     $collect_key_props Collect array keys and/or object properties?
     * @param null|int $x_flags           Any additional flags supported by `fnmatch()`.
     *
     * @return bool|array Will return `TRUE` if wildcard pattern finds a match.
     *                    If `$collect_key_props` is `TRUE`, this will return an array.
     */
    public function in($wildcard, $value, $caSe_insensitive = false, $collect_key_props = false, $x_flags = null)
    {
        if (isset($x_flags)) {
            $x_flags = (int) $x_flags;
        }
        if (!strlen($wildcard = (string) $wildcard)) {
            return $collect_key_props ? array() : false;
        }
        $matching_key_props = array(); // Initialize.
        $flags              = $caSe_insensitive ? FNM_CASEFOLD : 0;
        $flags              = isset($x_flags) ? $flags | $x_flags : $flags;

        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key_prop => $_value) {
                if (is_array($_value) || is_object($_value)) {
                    $_matching_key_props = $this->in(
                        $wildcard,
                        $_value,
                        $caSe_insensitive,
                        $collect_key_props,
                        $x_flags
                    );
                    if ($_matching_key_props) {
                        if ($collect_key_props) {
                            $matching_key_props[] = array($_key_prop => $_matching_key_props);
                        } else {
                            return true;
                        }
                    }
                } else {
                    $_value = (string) $_value;
                    if (fnmatch($wildcard, $_value, $flags)) {
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
            if (fnmatch($wildcard, $value, $flags)) {
                return true;
            }
        }
        return $collect_key_props ? $matching_key_props : false;
    }
}
