<?php
/**
 * HTML strip utilities.
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
 * HTML strip utilities.
 *
 * @since 150424 Initial release.
 */
class HtmlStrip extends Classes\Core\Base\Core
{
    /**
     * Strips HTML attributes deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value Any input value.
     * @param array $args  Any additional behavioral args.
     *
     * @return string|array|object String w/ HTML attributes stripped.
     */
    public function attributes($value, array $args = [])
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->attributes($_value, $args);
            } // unset($_key, $_value);
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        $default_args = [
            'allowed_attributes' => [],
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        $allowed_attributes = // Force lowercase.
            array_map('mb_strtolower', (array) $args['allowed_attributes']);

        $regex_tags  = '/(?<open>\<[\w\-]+)(?<attrs>[^>]+)(?<close>\>)/ui';
        $regex_attrs = '/\s+(?<attr>[\w\-]+)(?:\s*\=\s*(["\']).*?\\2|\s*\=[^\s]*)?/uis';

        return preg_replace_callback($regex_tags, function ($m) use ($allowed_attributes, $regex_attrs) {
            return $m['open'].preg_replace_callback($regex_attrs, function ($m) use ($allowed_attributes) {
                return in_array(mb_strtolower($m['attr']), $allowed_attributes, true) ? $m[0] : '';
            }, $m['attrs']).$m['close']; // With modified attributes.

        }, $string); // Removes attributes; leaving only those allowed explicitly.
    }
}
