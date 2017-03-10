<?php
/**
 * Paginator utils.
 *
 * @author @jaswrks
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
 * Paginator utils.
 *
 * @since 161006 Paginator utils.
 */
class Paginator extends Classes\Core\Base\Core
{
    /**
     * Gets paginator.
     *
     * @since 161006 Paginator utils.
     *
     * @param array $args Paginator args.
     *
     * @return Classes\Core\Paginator Paginator instance.
     */
    public function get(array $args = []): Classes\Core\Paginator
    {
        return $this->App->Di->get(Classes\Core\Paginator::class, compact('args'));
    }
}
