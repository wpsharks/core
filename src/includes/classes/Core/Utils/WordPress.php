<?php
/**
 * WP utilities.
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
 * WP utilities.
 *
 * @since 160219 WP utils.
 */
class WordPress extends Classes\Core\Base\Core
{
    /**
     * Is WordPress?
     *
     * @since 160219 WP utils.
     *
     * @return bool Is WordPress?
     */
    public function is(): bool
    {
        return defined('ABSPATH') || defined('WPINC');
    }
}
