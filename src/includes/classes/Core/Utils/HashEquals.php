<?php
/**
 * Hash equals.
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
#
use Hashids\Hashids as Parser;

/**
 * Hash equals.
 *
 * @since 17xxxx Initial release.
 */
class HashEquals extends Classes\Core\Base\Core
{
    /**
     * Hash equals.
     *
     * @since 17xxxx Hash equals utils.
     *
     * @param string $known_hash     Known hash.
     * @param string $given_hash     Given hash.
     * @param bool   $caSe_sensitive CaSe-sensitive?
     *
     * @return bool True if hashes are equal.
     */
    public function __invoke(string $known_hash, string $given_hash, bool $caSe_sensitive = false): bool
    {
        if (!$caSe_sensitive) {
            $known_hash = mb_strtolower($known_hash);
            $given_hash = mb_strtolower($given_hash);
        }
        return hash_equals($known_hash, $given_hash);
    }
}
