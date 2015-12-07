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
     * @since 151121 Template utilities.
     *
     * @param string $file    Relative to templates dir.
     * @param string $dir     From a specific directory?
     * @param array  $parents Parent template files.
     *
     * @return Classes\Template Template instance.
     */
    public function get(string $file, string $dir = '', array $parents = []): Classes\Template
    {
        if ($dir === 'core') {
            $dir = $this->¤core_dir.'/src/includes/templates';
        } elseif (!$dir && $this->App->Config->fs_paths['templates_dir']) {
            $dir = $this->App->Config->fs_paths['templates_dir'];
        }
        if (!$dir) { // Must have a directory.
            throw new Exception('Missing templates dir.');
        }
        $file = $this->Utils->Trim->l($file, '/');

        if (is_file($dir.'/'.$file)) {
            $file = $dir.'/'.$file;
        } else {
            $file = $this->¤core_dir.'/src/includes/templates/'.$file;
        }
        return $this->App->Di->get(Classes\Template::class, compact('file', 'parents'));
    }
}
