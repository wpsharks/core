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
     * @param string $file Relative to templates dir.
     * @param string $dir  From a specific directory?
     *
     * @return Classes\Template Template instance.
     */
    public function get(string $file, string $dir = ''): Classes\Template
    {
        if ($dir === 'core') {
            $dir = dirname(__FILE__, 3).'/templates';
        }
        if (!$dir && !$this->App->Config->fs_paths['templates_dir']) {
            throw new Exception('Missing templates dir.');
        }
        $file = $this->Utils->Trim->l($file, '/');

        if ($dir && is_file($dir.'/'.$file)) {
            $file = $dir.'/'.$file;
        } elseif (is_file($this->App->Config->fs_paths['templates_dir'].'/'.$file)) {
            $file = $this->App->Config->fs_paths['templates_dir'].'/'.$file;
        } else {
            $file = dirname(__FILE__, 3).'/templates/'.$file;
        }
        return $this->App->Di->get(Classes\Template::class, ['file' => $file]);
    }
}
