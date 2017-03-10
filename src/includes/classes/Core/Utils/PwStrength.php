<?php
/**
 * Password strength utils.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
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
 * Password strength utils.
 *
 * @since 150424 Adding password strength.
 */
class PwStrength extends Classes\Core\Base\Core
{
    /**
     * Password strength.
     *
     * @since 150424 Adding password strength.
     *
     * @param string $password Password to test.
     *
     * @return int Password strength (0 - 100).
     */
    public function __invoke(string $password): int
    {
        $score = 0; // Initialize score.

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
}
