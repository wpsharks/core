<?php
/**
 * Password utilities.
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
 * Password utilities.
 *
 * @since 150424 Initial release.
 */
class Password extends Classes\Core\Base\Core
{
    /**
     * Password strength.
     *
     * @since 17xxxx Password strength.
     *
     * @param string $password Password.
     *
     * @return int Strength (0 - 100).
     */
    public function strength(string $password): int
    {
        $score = 0; // Initialize.

        if (!isset($password[0])) {
            return $score;
        }
        if (preg_match('/\p{N}/u', $password)) {
            $score += 25; // Number.
        }
        if (preg_match('/\p{Ll}/u', $password)) {
            $score += 25; // Lowercase.
        }
        if (preg_match('/\p{Lu}/u', $password)) {
            $score += 25; // Uppercase.
        }
        if (preg_match('/[^\p{N}\p{Ll}\p{Lu}]/u', $password)) {
            $score += 25; // Special symbol.
        }
        return $score;
    }

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
            throw $this->c::issue('Missing password hash key.');
        }
        return $this->c::sha256KeyedHash($string, $key);
    }
}
