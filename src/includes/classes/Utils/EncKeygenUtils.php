<?php
namespace WebSharks\Core\Classes\Utils;

/**
 * Enc. keygen utilities.
 *
 * @since 150424 Initial release.
 */
class EncKeygenUtils extends AbsBase
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
    public function encKeygenRandom($length = 15, $special_chars = true, $extra_special_chars = false)
    {
        $length = max(0, (integer) $length);

        $chars  = 'abcdefghijklmnopqrstuvwxyz';
        $chars .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

        if ($special_chars) {
            $chars .= '!@#$%^&*()';
        }
        if ($extra_special_chars) {
            $chars .= '-_[]{}<>~`+=,.;:/?|';
        }
        $total_chars = strlen($chars);
        for ($key = '', $_i = 0; $_i < $length; $_i++) {
            $key .= substr($chars, mt_rand(0, $total_chars - 1), 1);
        }
        unset($_i); // Housekeeping.

        return $key; // Generated randomly.
    }

    /**
     * A unique, unguessable, non-numeric, caSe-insensitive key (20 chars max).
     *
     * @since 150424 Initial release.
     *
     * @note 32-bit systems usually have `PHP_INT_MAX` = `2147483647`.
     *    We limit `mt_rand()` to a max of `999999999`.
     *
     * @note A max possible length of 20 chars assumes this function
     *    will not be called after `Sat, 20 Nov 2286 17:46:39 GMT`.
     *    At which point a UNIX timestamp will grow in size.
     *
     * @note Key always begins with a `k` to prevent PHP's `is_numeric()`
     *    function from ever thinking it's a number in a different representation.
     *    See: <http://php.net/manual/en/function.is-numeric.php> for further details.
     *
     * @return string A unique, unguessable, non-numeric, caSe-insensitive key (20 chars max).
     */
    public function encKeygenUunnci20Max()
    {
        $microtime_19_max = number_format(microtime(true), 9, '.', '');
        // e.g. `9999999999`.`999999999` (max decimals: `9`, max overall precision: `19`).
        // Assuming timestamp is never > 10 digits; i.e. before `Sat, 20 Nov 2286 17:46:39 GMT`.

        list($seconds_10_max, $microseconds_9_max) = explode('.', $microtime_19_max, 2);
        // e.g. `array(`9999999999`, `999999999`)`. Max total digits combined: `19`.

        $seconds_base36      = base_convert($seconds_10_max, '10', '36'); // e.g. max `9999999999`, to base 36.
        $microseconds_base36 = base_convert($microseconds_9_max, '10', '36'); // e.g. max `999999999`, to base 36.
        $mt_rand_base36      = base_convert(mt_rand(1, 999999999), '10', '36'); // e.g. max `999999999`, to base 36.
        $key                 = 'k'.$mt_rand_base36.$seconds_base36.$microseconds_base36; // e.g. `kgjdgxr4ldqpdrgjdgxr`.

        return $key; // Max possible value: `kgjdgxr4ldqpdrgjdgxr` (20 chars).
    }
}
