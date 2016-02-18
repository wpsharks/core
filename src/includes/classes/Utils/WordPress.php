<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Functions\__;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * WP utilities.
 *
 * @since 160219 WP utils.
 */
class WordPress extends Classes\AppBase
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
        return defined('WPINC');
    }
}
