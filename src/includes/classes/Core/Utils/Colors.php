<?php
/**
 * Color utilities.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Color utilities.
 *
 * @since 161010 Color utilities.
 */
class Colors extends Classes\Core\Base\Core
{
    /**
     * Contrasting foreground color.
     *
     * @since 161010 Color utilities.
     *
     * @param string $bg_hex Background color.
     *
     * @return string Suggested foreground color.
     */
    public function contrastingFg(string $bg_hex): string
    {
        return hexdec($bg_hex) > 0xffffff / 2 ? '#000' : '#fff';
    }
}
