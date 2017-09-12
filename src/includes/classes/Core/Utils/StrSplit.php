<?php
/**
 * Multibyte `str_split()`.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Multibyte `str_split()`.
 *
 * @since 150424 Enhancing multibyte support.
 */
class StrSplit extends Classes\Core\Base\Core
{
    /**
     * Multibyte `str_split()`.
     *
     * @since 150424 Enhancing multibyte support.
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
            throw $this->c::issue('Length < 1.');
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
