<?php
namespace WebSharks\Core\Classes\Utils;

/**
 * RIJNDAEL 256 encryption utilities.
 *
 * @since 150424 Initial release.
 */
class EncRij256Utils extends AbsBase
{
    protected $EncShaUtils;
    protected $EnvUtils;
    protected $EncKeygenUtils;
    protected $EncBase64Utils;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(
        EncShaUtils $EncShaUtils,
        EnvUtils $EnvUtils,
        EncKeygenUtils $EncKeygenUtils,
        EncBase64Utils $EncBase64Utils
    ) {
        parent::__construct();

        $this->EncShaUtils    = $EncShaUtils;
        $this->EnvUtils       = $EnvUtils;
        $this->EncKeygenUtils = $EncKeygenUtils;
        $this->EncBase64Utils = $EncBase64Utils;
    }

    /**
     * RIJNDAEL 256 encryption with a URL-safe base64 wrapper.
     *
     * @since 150424 Initial release.
     *
     * @param string $string   A string of data to encrypt.
     * @param string $key      Optional. Key to use in encryption.
     * @param bool   $w_md5_cs Optional. Defaults to `TRUE` (recommended).
     *
     * @throws \Exception If string encryption fails.
     *
     * @return string Encrypted string.
     */
    public function encRij256Encrypt($string, $key = '', $w_md5_cs = true)
    {
        if (!$this->EnvUtils->envHasExtension('mcrypt')) {
            throw new \Exception('Mcrypt extension missing.');
        }
        $string = (string) $string;
        if (!isset($string[0])) {
            return ($base64 = '');
        }
        $key    = $this->EncShaUtils->encShaXSigKey((string) $key);
        $string = '~r2|'.$string; // A short `RIJNDAEL 256` identifier.
        $key    = (string) substr($key, 0, mcrypt_get_key_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC));
        $iv     = $this->EncKeygenUtils->encKeygenRandom(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC), false);

        if (!is_string($e = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $string, MCRYPT_MODE_CBC, $iv)) || !isset($e[0])) {
            throw new \Exception('String encryption failed; `$e` is NOT string; or it has no length.');
        }
        $e = '~r2:'.$iv.($w_md5_cs ? ':'.md5($e) : '').'|'.$e; // Pack components.

        return ($base64 = $this->EncBase64Utils->encBase64UrlSafeEncode($e));
    }

    /**
     * RIJNDAEL 256 decryption with a URL-safe base64 unwrapper.
     *
     * @since 150424 Initial release.
     *
     * @param string $base64 A string of data to decrypt (still base64 encoded).
     * @param string $key    Optional. Key originally used for encryption.
     *
     * @throws \Exception If a validated RIJNDAEL 256 string decryption fails.
     *
     * @return string Decrypted string, or an empty string if validation fails.
     */
    public function encRij256Decrypt($base64, $key = '')
    {
        if (!$this->EnvUtils->envHasExtension('mcrypt')) {
            throw new \Exception('Mcrypt extension missing.');
        }
        $base64 = (string) $base64;
        if (!isset($base64[0])) {
            return ($string = '');
        }
        $key = $this->EncShaUtils->encShaXSigKey((string) $key);

        if (!strlen($e = $this->EncBase64Utils->encBase64UrlSafeDecode($base64))
           || !preg_match('/^~r2\:(?P<iv>[a-zA-Z0-9]+)(?:\:(?P<md5>[a-zA-Z0-9]+))?\|(?P<e>.*)$/s', $e, $iv_md5_e)
        ) {
            return ($string = ''); // Not possible; unable to decrypt in this case.
        }
        if (!isset($iv_md5_e['iv'][0], $iv_md5_e['e'][0])) {
            return ($string = ''); // Components missing.
        }
        if (isset($iv_md5_e['md5'][0]) && $iv_md5_e['md5'] !== md5($iv_md5_e['e'])) {
            return ($string = ''); // Invalid checksum; automatic failure.
        }
        $key = (string) substr($key, 0, mcrypt_get_key_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC));

        if (!is_string($string = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $iv_md5_e['e'], MCRYPT_MODE_CBC, $iv_md5_e['iv'])) || !isset($string[0])) {
            throw new \Exception('String decryption failed; `$string` is NOT a string, or it has no length.');
        }
        if (!strlen($string = preg_replace('/^~r2\|/', '', $string, 1, $r2)) || !$r2) {
            return ($string = ''); // Missing packed components.
        }
        return ($string = rtrim($string, "\0\4")); // See: <http://www.asciitable.com/>.
    }
}
