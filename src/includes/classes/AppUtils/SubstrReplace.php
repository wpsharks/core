<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Multibyte `substr_replace()`.
 *
 * @since 15xxxx Enhancing multibyte support.
 */
class SubstrReplace extends Classes\AbsBase
{
    /**
     * Multibyte `substr_replace()`.
     *
     * @since 15xxxx Enhancing multibyte support.
     *
     * @param string   $string  Input string to search/replace.
     * @param string   $replace String to use as a replacement.
     * @param int      $start   Substring start position.
     * @param int|null $length  Substring length.
     *
     * @return string String after replacement.
     *
     * @see http://php.net/manual/en/function.substr-replace.php
     *
     * @note This variation does NOT support mixed input args like `substr_replace()` does.
     */
    public function __invoke(string $string, string $replace, int $start, int $length = null): string
    {
        if (!isset($string[0])) {
            return $string;
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
        return mb_substr($string, 0, $start).$replace.mb_substr($string, $start + $length, $mb_strlen - $start - $length);
    }
}
