<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Template utilities.
 *
 * @since 151121 Template utilities.
 */
class Template extends Classes\AppBase
{
    /**
     * Gets template.
     *
     * @since 151121 Template utilities.
     *
     * @param string $file        Relative to templates dir.
     * @param string $dir         From a specific directory?
     * @param array  $parents     Parent template files.
     * @param array  $parent_vars Parent template vars.
     *
     * @return Classes\Template Template instance.
     */
    public function get(
        string $file,
        string $dir = '',
        array $parents = [],
        array $parent_vars = []
    ): Classes\Template {
        $args = compact('dir', 'file', 'parents', 'parent_vars');
        return c\di_get(Classes\Template::class, $args);
    }
}
