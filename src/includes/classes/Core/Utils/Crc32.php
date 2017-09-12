<?php
/**
 * CRC-32 utilities.
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
 * CRC-32 utilities.
 *
 * @since 170824.30708 Initial release.
 */
class Crc32 extends Classes\Core\Base\Core
{
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
            if (!($key = $this->App->Config->©encryption['©key'])) {
                throw $this->c::issue('Missing HMAC hash key.');
            } // @TODO Remove encryption key fallback.
            // It's only here for backward compatibility.
            // i.e., For apps missing a generic hash key.
        }
        return hash_hmac('crc32b', $string, $key);
    }
}
