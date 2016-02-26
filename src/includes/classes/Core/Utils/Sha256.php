<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

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
    public function keyedHash(string $string, string $key): string
    {
        return hash_hmac('sha256', $string, $key);
    }
}
