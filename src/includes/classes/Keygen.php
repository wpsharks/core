<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * Enc. keygen utilities.
 *
 * @since 150424 Initial release.
 */
class Keygen extends AbsBase
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
     * Generates a random key.
     *
     * @since 150424 Initial release.
     *
     * @param int  $length              Length of the key. Default is `15`.
     * @param bool $special_chars       Include standard special characters? Defaults to `TRUE`.
     * @param bool $extra_special_chars Include extra special characters? Defaults to `FALSE`.
     *
     * @return string The random key.
     */
    public function random(int $length = 15, bool $special_chars = true, bool $extra_special_chars = false): string
    {
        $length = max(0, $length);

        $chars = 'abcdefghijklmnopqrstuvwxyz';
        $chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        if ($special_chars) {
            $chars .= '!@#$%^&*()';
        }
        if ($extra_special_chars) {
            $chars .= '-_[]{}<>~`+=,.;:/?|';
        }
        $total_chars = mb_strlen($chars);
        for ($key = '', $_i = 0; $_i < $length; ++$_i) {
            $key .= mb_substr($chars, mt_rand(0, $total_chars - 1), 1);
        } // unset($_i); // Housekeeping.

        return $key;
    }
}
