<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Template utilities.
 *
 * @since 151121 Template utilities.
 */
class Template extends Classes\AbsBase
{
    /**
     * Gets template.
     *
     * @since 151121 Header utilities.
     *
     * @param string $file Template file (relative to templates dir).
     *
     * @return Classes\Template Class instance.
     */
    public function get(string $file): Classes\Template
    {
        $file = $this->App->Config->templates_dir.'/'.$file;
        return $this->App->Di->get(Classes\Template::class, compact('file', 'vars'));
    }
}
