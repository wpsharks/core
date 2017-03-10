<?php
/**
 * Defuse encryption.
 *
 * @author @jaswrks
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
#
use Defuse\Crypto\Key;
use Defuse\Crypto\Crypto;

/**
 * Defuse encryption.
 *
 * @since 170309.60830 Defuse.
 */
class Defuse extends Classes\Core\Base\Core
{
    /**
     * Defuse keygen.
     *
     * @since 170309.60830 Defuse.
     *
     * @return string Defuse key.
     * @note Keys begin w/ `def00000`.
     */
    public function keygen(): string
    {
        try { // Catch Defuse exceptions.
            if (!($key = Key::createNewRandomKey()->saveToAsciiSafeString())) {
                throw new Exception('Defuse keygen failure.');
            }
        } catch (\Throwable $Exception) {
            throw $this->c::issue($Exception->getMessage());
        }
        return $key;
    }

    /**
     * Defuse encryption.
     *
     * @since 170309.60830 Defuse.
     *
     * @param string $string String.
     * @param string $key    Encryption key.
     * @param bool   $raw    Return raw binary?
     *
     * @return string Raw binary or hex (default).
     */
    public function encrypt(string $string, string $key = '', bool $raw = false): string
    {
        if (!$key && !($key = $this->App->Config->©encryption['©key'])) {
            throw $this->c::issue('Missing encryption key.');
        }
        if (mb_strpos($key, 'def00000') !== 0) {
            return $this->c::rjEncrypt($string, $key);
        } // @TODO Remove; only for back compat.

        try { // Catch Defuse exceptions.
            $Key              = Key::loadFromAsciiSafeString($key);
            return $encrypted = Crypto::encrypt($string, $Key, $raw);
        } catch (\Throwable $Exception) {
            throw $this->c::issue($Exception->getMessage());
        }
    }

    /**
     * Defuse decryption.
     *
     * @since 170309.60830 Defuse.
     *
     * @param string $string String.
     * @param string $key    Decryption key.
     * @param bool   $raw    Is raw binary?
     *
     * @return string Decrypted string.
     */
    public function decrypt(string $string, string $key = '', bool $raw = false): string
    {
        if (!$key && !($key = $this->App->Config->©encryption['©key'])) {
            throw $this->c::issue('Missing decryption key.');
        }
        if (mb_strpos($key, 'def00000') !== 0) {
            return $this->c::rjDecrypt($string, $key);
        } // @TODO Remove; only for back compat.

        try { // Catch Defuse exceptions.
            $Key              = Key::loadFromAsciiSafeString($key);
            return $decrypted = Crypto::decrypt($string, $Key, $raw);
        } catch (\Throwable $Exception) {
            return ''; // Soft failure.
        }
    }
}
