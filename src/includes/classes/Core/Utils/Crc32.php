<?php
/**
 * CRC-32 utils.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
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
 * CRC-32 utils.
 *
 * @since 170824.30708 Initial release.
 */
class Crc32 extends Classes\Core\Base\Core
{
    /**
     * Is a CRC-32 hash?
     *
     * @since 17xxxx CRC-32 utils.
     *
     * @param mixed $value Value.
     *
     * @return bool True if a CRC-32 hash.
     */
    public function is($value): bool
    {
        if (!$value) {
            return false;
        } elseif (!is_string($value)) {
            return false;
        } elseif (strlen($value) !== 8) {
            return false;
        } elseif (!ctype_xdigit($value)) {
            return false;
        }
        return true;
    }

    /**
     * Generates a keyed CRC-32 signature.
     *
     * @since 170824.30708 Initial release.
     *
     * @param string $string String to sign.
     * @param string $key    Encryption key.
     *
     * @return string CRC-32 signature string (8 bytes).
     */
    public function keyedHash(string $string, string $key = ''): string
    {
        if (!$key && !($key = $this->App->Config->©hash['©key'])) {
            throw $this->c::issue('Missing HMAC hash key.');
        }
        return hash_hmac('crc32b', $string, $key);
    }
}
