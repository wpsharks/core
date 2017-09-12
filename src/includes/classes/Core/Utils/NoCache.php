<?php
/**
 * No-cache utils.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
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
 * No-cache utils.
 *
 * @since 160606 No-cache utils.
 */
class NoCache extends Classes\Core\Base\Core
{
    /**
     * Set no-cache flags.
     *
     * @since 160606 No-cache utils.
     */
    public function setFlags()
    {
        if ($this->c::isWordPress()) {
            if (!defined('DONOTCACHEPAGE')) {
                define('DONOTCACHEPAGE', true);
            }
            if (!defined('COMET_CACHE_ALLOWED')) {
                define('COMET_CACHE_ALLOWED', false);
            }
            $_SERVER['COMET_CACHE_ALLOWED'] = false;
        }
    }
}
