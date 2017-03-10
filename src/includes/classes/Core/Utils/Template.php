<?php
/**
 * Template utilities.
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
 * Template utilities.
 *
 * @since 151121 Template utilities.
 */
class Template extends Classes\Core\Base\Core
{
    /**
     * Locates a template file.
     *
     * @since 160118 Template locater.
     *
     * @param string $file Relative to templates dir.
     * @param string $dir  From a specific directory?
     *
     * @return array Template `dir`, `file`, and `ext`.
     */
    public function locate(string $file, string $dir = ''): array
    {
        $is_file = false; // Initialize.
        $file    = $this->c::mbTrim($file, '/');
        $dir     = $this->c::mbRTrim($dir, '/');
        $dir     = $dir ?: $this->App->Config->©fs_paths['©templates_dir'];

        if ($dir === 'core') {
            if ($this->App->Parent) {
                return $this->App->Parent->Utils->©Template->locate($file, $dir);
            } else { // Use core config option.
                $dir = $this->App->Config->©fs_paths['©templates_dir'];
            }
        } elseif ($dir === 'parent' || ($dir && $file && !($is_file = is_file($dir.'/'.$file)))) {
            if ($this->App->Parent) {
                return $this->App->Parent->Utils->©Template->locate($file);
            } else { // Else go with what we have.
                $dir = $this->App->Config->©fs_paths['©templates_dir'];
            }
        }
        if ($dir && $file && !$is_file && !($is_file = is_file($dir.'/'.$file))) {
            $dir = dirname(__FILE__, 4).'/templates'; // WS core templates.
        }
        if ($dir && $file && ($is_file || ($is_file = is_file($dir.'/'.$file)))) {
            if (preg_match('/\/\.|\.\/|\.\./u', $this->c::normalizeDirPath($dir.'/'.$file))) {
                throw $this->c::issue(sprintf('Insecure template path: `%1$s`.', $dir.'/'.$file));
            }
            return ['dir' => $dir, 'file' => $file, 'ext' => $this->c::fileExt($file)];
        }
        return []; // Unable to locate.
    }

    /**
     * Gets template.
     *
     * @since 151121 Template utilities.
     *
     * @param string $file Relative to templates dir.
     * @param string $dir  From a specific directory?
     * @param array  $args Additional template args.
     *
     * @return Classes\Core\Template Template instance.
     */
    public function get(string $file, string $dir = '', array $args = []): Classes\Core\Template
    {
        return $this->App->Di->get(Classes\Core\Template::class, compact('dir', 'file', 'args'));
    }
}
