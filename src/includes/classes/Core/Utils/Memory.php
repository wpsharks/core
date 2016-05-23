<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
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
     *
     * @param string|null $limit Size abbr.
     *
     * @return float Current max memory; in bytes.
     */
    public function limit(string $limit = null): float
    {
        if (isset($limit) && $limit) {
            @ini_set('memory_limit', $limit);
        }
        return $this->c::abbrToBytes(ini_get('memory_limit'));
    }
}
