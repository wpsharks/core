<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * CLI utilities.
 *
 * @since 150424 Initial release.
 */
class Cli extends Classes\AbsBase
{
    /**
     * Running in a command line interface?
     *
     * @since 150424 Initial release.
     *
     * @return bool `TRUE` if running in a command line interface.
     */
    public function is(): bool
    {
        if (!is_null($is = &$this->cacheKey(__FUNCTION__))) {
            return $is; // Cached this already.
        }
        if ($this->Utils->StrCaseCmp(PHP_SAPI, 'cli') === 0) {
            return ($is = true);
        }
        return ($is = false);
    }
}
