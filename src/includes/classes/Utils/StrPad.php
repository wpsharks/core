<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Functions\__;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Multibyte `str_pad()`.
 *
 * @since 150424 Enhancing multibyte support.
 */
class StrPad extends Classes\AppBase
{
    /**
     * Multibyte `str_pad()`.
     *
     * @since 150424 Enhancing multibyte support.
     *
     * @param mixed  $value      Any input value.
     * @param int    $pad_length The required length of the string.
     * @param string $pad_string The string to pad with.
     * @param int    $pad_type   `STR_PAD_LEFT`, `STR_PAD_RIGHT`, `STR_PAD_BOTH`.
     *
     * @return string|array|object Output value.
     */
    public function __invoke($value, int $pad_length, string $pad_string = ' ', int $pad_type = STR_PAD_RIGHT): string
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($_value, $pad_length, $pad_string, $pad_type);
            } // unset($_key, $_value);
            return $value;
        }
        $string    = (string) $value;
        $mb_strlen = mb_strlen($string);

        if ($pad_length < 0 || $pad_length <= $mb_strlen) {
            return $string; // Nothing to do.
        }
        $pad_string_mb_strlen = mb_strlen($pad_string);

        switch ($pad_type) {
            case STR_PAD_LEFT:
                $repeat = ceil(max(0, $mb_strlen - $pad_string_mb_strlen) + $pad_length);
                $string = str_repeat($pad_string, $repeat).$string;
                return mb_substr($string, -$pad_length);

            case STR_PAD_RIGHT:
                $repeat = ceil(max(0, $mb_strlen - $pad_string_mb_strlen) + $pad_length);
                $string = $string.str_repeat($pad_string, $repeat);
                return mb_substr($string, 0, $pad_length);

            case STR_PAD_BOTH:
                $half_pad_length = ($pad_length - $mb_strlen) / 2;
                $split_repeat    = ceil($pad_length / $pad_string_mb_strlen);
                return mb_substr(str_repeat($pad_string, $split_repeat), 0, floor($half_pad_length))
                        .$string.mb_substr(str_repeat($pad_string, $split_repeat), 0, ceil($half_pad_length));

            default: // Exception on unexpected pad type.
                throw new Exception('Unexpected `pad_type`.');
        }
    }
}
