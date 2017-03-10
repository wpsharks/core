<?php
/**
 * EOL utilities.
 *
 * @author @jaswrks
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
 * EOL utilities.
 *
 * @since 150424 Initial release.
 */
class Eols extends Classes\Core\Base\Core
{
    /**
     * Normalizes end of line chars deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value    Any input value.
     * @param bool  $compress Compress `\n{3,}` into `\n\n`?
     *
     * @return string|array|object With normalized end of line chars deeply.
     */
    public function normalize($value, bool $compress = false)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->normalize($_value, $compress);
            } // unset($_key, $_value);
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        $string = str_replace(["\r\n", "\r"], "\n", $string);
        $string = $compress ? preg_replace('/'."\n".'{3,}/u', "\n\n", $string) : $string;

        return $string;
    }
}
