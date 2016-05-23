<?php
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
 * RIJNDAEL 256 encryption utilities.
 *
 * @since 150424 Initial release.
 */
class Rijndael256 extends Classes\Core\Base\Core
{
    /**
     * IV size.
     *
     * @since 150424
     *
     * @type int
     */
    protected $iv_size;

    /**
     * Key size.
     *
     * @since 150424
     *
     * @type int
     */
    protected $key_size;

    /**
     * Class constructor.
     *
     * @since 150424 Initial release.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        $this->iv_size  = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
        $this->key_size = mcrypt_get_key_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
    }

    /**
     * RIJNDAEL 256 encryption with a URL-safe base64 wrapper.
     *
     * @since 150424 Initial release.
     *
     * @param string $string String to encrypt.
     * @param string $key    Encryption key (required always).
     * @param bool   $sha1   Defaults to `true` (recommended).
     *
     * @return string Encrypted string (URL-safe base64).
     */
    public function encrypt(string $string, string $key, bool $sha1 = true): string
    {
        if (!isset($string[0])) {
            return $base64 = ''; // Nothing to do.
        }
        $string = '~r2|'.$string; // `RIJNDAEL 256` identifier.

        if (strlen($key = (string) substr($key, 0, $this->key_size)) < $this->key_size) {
            throw new Exception(sprintf('Key too short. Minimum length: `%1$s`.', $this->key_size));
        }
        if (strlen($iv = $this->c::randomKey($this->iv_size, false)) < $this->iv_size) {
            throw new Exception(sprintf('IV too short. Minimum length: `%1$s`.', $this->iv_size));
        }
        if (!is_string($e = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_CBC, $iv)) || !isset($e[0])) {
            throw new Exception('String encryption failed; `$e` is not a string; or it has no length.');
        }
        $e = '~r2:'.$iv.($sha1 ? ':'.sha1($e) : '').'|'.$e; // Pack components.

        return $base64 = $this->c::base64UrlSafeEncode($e);
    }

    /**
     * RIJNDAEL 256 decryption with a URL-safe base64 unwrapper.
     *
     * @since 150424 Initial release.
     *
     * @param string $base64 String to decrypt (URL-safe base64).
     * @param string $key    Encryption key (required always).
     *
     * @return string Decrypted string; or empty string on failure.
     */
    public function decrypt(string $base64, string $key): string
    {
        if (!isset($base64[0])) {
            return $string = '';
        }
        $regex = // Matches a valid encryption.

          '/^'.// Beginning of the strong.
            '~r2'.// Required RIJNDAEL 256 marker.
            '\:(?<iv>[a-zA-Z0-9]{'.$this->iv_size.'})'.// Required IV.
            '(?:\:(?<sha1>[a-zA-Z0-9]{40}))?'.// Optional checksum.
            '\|(?<e>.+)'.// Encrypted string (not empty).
          '$/s'; // End of string.

        if (!($e = $this->c::base64UrlSafeDecode($base64))) {
            return $string = ''; // Not possible.
        }
        if (!preg_match($regex, $e, $iv_sha1_e)) {
            return $string = ''; // Not possible.
        }
        if (!empty($iv_sha1_e['sha1']) && $iv_sha1_e['sha1'] !== sha1($iv_sha1_e['e'])) {
            return $string = ''; // Invalid checksum; automatic failure.
        }
        if (strlen($key = (string) substr($key, 0, $this->key_size)) < $this->key_size) {
            throw new Exception(sprintf('Key too short. Minimum length: `%1$s`.', $this->key_size));
        }
        if (!is_string($string = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $iv_sha1_e['e'], MCRYPT_MODE_CBC, $iv_sha1_e['iv'])) || !isset($string[0])) {
            throw new Exception('String decryption failed; `$string` is NOT a string, or it has no length.');
        }
        if (!strlen($string = preg_replace('/^~r2\|/', '', $string, 1, $r2)) || !$r2) {
            return $string = ''; // Missing packed component identifier.
        }
        return $string = $this->c::mbRTrim($string, "\0\4"); // See: <http://www.asciitable.com/>.
    }
}
