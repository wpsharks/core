<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Functions\__;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Password utilities.
 *
 * @since 150424 Initial release.
 */
class Password extends Classes\AppBase
{
    /**
     * Generates a password hash.
     *
     * @since 150424 Initial release.
     *
     * @param string $string String to sign.
     * @param string $key    Encryption key.
     *
     * @return string Hash (a keyed SHA-256 signature; 64 chars).
     */
    public function sha256(string $string, string $key = ''): string
    {
        if (!$key && !($key = $this->App->Config->passwords['hash_key'])) {
            throw new Exception('Missing password hash key.');
        }
        return c\sha256_keyed_hash($string, $key);
    }
}
