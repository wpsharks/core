<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Multibyte `str_split()`.
 *
 * @since 15xxxx Enhancing multibyte support.
 */
class StrSplit extends Classes\AbsBase
{
    /**
     * Multibyte `str_split()`.
     *
     * @since 15xxxx Enhancing multibyte support.
     *
     * @param string $string       Input string to split.
     * @param int    $split_length Split length; i.e., chunk size.
     *
     * @return array An array of characters; in chunk size.
     */
    public function __invoke(string $string, int $split_length = 1): array
    {
        if (!isset($string[0])) {
            return []; // Nothing to do.
        }
        if ($split_length < 1) {
            throw new Exception('Length < 1.');
        }
        if ($split_length > 1) {
            $chunks    = [];
            $mb_strlen = mb_strlen($string);
            for ($_i = 0; $_i < $mb_strlen; $_i += $split_length) {
                $chunks[] = mb_substr($string, $_i, $split_length);
            } // unset($_i); // Housekeeping.
            return $chunks;
        }
        return preg_split('//u', $string, -1, PREG_SPLIT_NO_EMPTY);
    }
}
