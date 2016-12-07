<?php
/**
 * Multibyte `substr_replace()`.
 *
 * @author @jaswsinc
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
 * Multibyte `substr_replace()`.
 *
 * @since 150424 Enhancing multibyte support.
 */
class SubstrReplace extends Classes\Core\Base\Core
{
    /**
     * Multibyte `substr_replace()`.
     *
     * @since 150424 Enhancing multibyte.
     *
     * @param mixed    $value   Input value.
     * @param string   $replace Replacement string.
     * @param int      $start   Substring start position.
     * @param int|null $length  Substring length.
     *
     * @return string|array|object Output value.
     *
     * @link http://php.net/manual/en/function.substr-replace.php
     *
     * @warning Does NOT support mixed `$replace`, `$start`, `$length` params like `substr_replace()` does.
     */
    public function __invoke($value, string $replace, int $start, int $length = null)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($_value, $replace, $start, $length);
            } // unset($_key, $_value);
            return $value;
        }
        $string = (string) $value;

        if (!isset($string[0])) {
            return $string; // Nothing to do.
        }
        $mb_strlen = mb_strlen($string);

        if ($start < 0) {
            $start = max(0, $mb_strlen + $start);
        } elseif ($start > $mb_strlen) {
            $start = $mb_strlen;
        }
        if ($length < 0) {
            $length = max(0, $mb_strlen - $start + $length);
        } elseif (!isset($length) || $length > $mb_strlen) {
            $length = $mb_strlen;
        }
        if ($start + $length > $mb_strlen) {
            $length = $mb_strlen - $start;
        }
        return mb_substr($string, 0, $start).
                $replace.// The replacement string.
            mb_substr($string, $start + $length, $mb_strlen - $start - $length);
    }
}
