<?php
/**
 * Sha256 utilities.
 *
 * @author @jaswsinc
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

/**
 * Sha256 utilities.
 *
 * @since 150424 Initial release.
 */
class Sha256 extends Classes\Core\Base\Core
{
    /**
     * Generates a keyed SHA-256 signature.
     *
     * @since 150424 Initial release.
     *
     * @param string $string String to sign.
     * @param string $key    Encryption key.
     *
     * @return string SHA-256 signature string (64 bytes).
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
        return hash_hmac('sha256', $string, $key);
    }
}
