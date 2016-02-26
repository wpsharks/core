<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Password utilities.
 *
 * @since 150424 Initial release.
 */
class Password extends Classes\Core\Base\Core
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
        if (!$key && !($key = $this->App->Config->©passwords['©hash_key'])) {
            throw new Exception('Missing password hash key.');
        }
        return $this->c::sha256KeyedHash($string, $key);
    }
}
