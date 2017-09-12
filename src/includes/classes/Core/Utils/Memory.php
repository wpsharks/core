<?php
/**
 * Memory utilities.
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
 * Memory utilities.
 *
 * @since 150424 Initial release.
 */
class Memory extends Classes\Core\Base\Core
{
    /**
     * Get/set available memory.
     *
     * @since 150424 Initial release.
     * @since 170824.30708 Returns `int` instead of `float`.
     *
     * @param string|null $limit Size abbr.
     *
     * @return int Current max memory, in bytes.
     */
    public function limit(string $limit = null): int
    {
        if (isset($limit) && $limit) {
            @ini_set('memory_limit', $limit);
        }
        return $this->c::abbrToBytes(ini_get('memory_limit'));
    }
}
