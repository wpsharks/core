<?php
/**
 * SHA-1 utils.
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
 * SHA-1 utils.
 *
 * @since 170824.30708 SHA-1 utils.
 */
class Sha1 extends Classes\Core\Base\Core
{
    /**
     * Is a SHA-1 hash?
     *
     * @since 170824.30708 SHA-1 utils.
     *
     * @param mixed $value Value.
     *
     * @return bool True if a SHA-1 hash.
     */
    public function is($value): bool
    {
        if (!$value) {
            return false;
        } elseif (!is_string($value)) {
            return false;
        } elseif (strlen($value) !== 40) {
            return false;
        } elseif (!ctype_xdigit($value)) {
            return false;
        }
        return true;
    }
}
