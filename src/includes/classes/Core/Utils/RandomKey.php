<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * RandomKey utilities.
 *
 * @since 150424 Initial release.
 */
class RandomKey extends Classes\Core\Base\Core
{
    /**
     * Generates a random key.
     *
     * @since 150424 Initial release.
     *
     * @param int  $char_size           Key char size. Default is `15`.
     * @param bool $special_chars       Include standard special characters? Defaults to `true`.
     * @param bool $extra_special_chars Include extra special characters? Defaults to `false`.
     *
     * @return string The random key.
     */
    public function __invoke(int $char_size = 15, bool $special_chars = true, bool $extra_special_chars = false): string
    {
        $char_size = max(0, $char_size);

        $chars = 'abcdefghijklmnopqrstuvwxyz';
        $chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        if ($special_chars) {
            $chars .= '!@#$%^&*()';
        }
        if ($special_chars && $extra_special_chars) {
            $chars .= '-_[]{}<>~`+=,.;:/?|';
        }
        $total_chars = mb_strlen($chars);

        for ($key = '', $_i = 0; $_i < $char_size; ++$_i) {
            $key .= mb_substr($chars, mt_rand(0, $total_chars - 1), 1);
        } // unset($_i); // Housekeeping.

        return $key;
    }
}
