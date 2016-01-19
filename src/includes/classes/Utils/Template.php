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
        $dir  = c\mb_rtrim($dir, '/');
        $file = c\mb_trim($file, '/');

        if (!$dir) { // Use default templates directory?
            $dir = $this->App->Config->fs_paths['templates_dir'];
        }
        if (!$dir || $dir === 'core' || ($file && !is_file($dir.'/'.$file))) {
            $dir = $this->App->core_dir.'/src/includes/templates';
        }
        if ($dir && $file && is_file($dir.'/'.$file)) {
            if (preg_match('/\/\.|\.\/|\.\./u', c\normalize_dir_path($dir.'/'.$file))) {
                throw new Exception(sprintf('Insecure template path: `%1$s`.', $dir.'/'.$file));
            }
            return ['dir' => $dir, 'file' => $file, 'ext' => c\file_ext($file)];
        }
        return []; // Unable to locate.
    }

    /**
     * Gets template for a route.
     *
     * @since 160118 Router templates.
     *
     * @param string $route A URL path/route.
     * @param string $dir   From a specific directory?
     * @param array  $args  Any additional behavioral args.
     *
     * @return array Route template `dir`, `file`, and `ext`.
     */
    public function locateRoute(string $route = '', string $dir = '', array $args = []): array
    {
        $default_args = [
            'protocol' => 'http',
            'ext'      => 'php',
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        $protocol = (string) $args['protocol'];
        $ext      = (string) $args['ext'];

        $route = c\mb_trim($route ?: c\current_path(), '/');
        $route = !isset($route[0]) ? 'index' : $route;

        return $this->locate($protocol.'/routes/'.$route.'.'.$ext, $dir);
    }

    /**
     * Loads template for a route.
     *
     * @since 160118 Router templates.
     *
     * @param string $route A URL path/route.
     * @param string $dir   From a specific directory?
     * @param array  $args  Any additional behavioral args.
     *
     * @return bool True if the route was successfully loaded.
     */
    public function loadRoute(string $route = '', string $dir = '', array $args = []): bool
    {
        $default_args = [
            'display_error' => true,
            'locate'        => [],
            'status_header' => [],
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        $display_error = (bool) $args['display_error'];

        if (($template = $this->locateRoute($route, $dir, $args['locate']))) {
            echo $this->get($template['file'], $template['dir'])->parse();
            return true; // Loaded successfully.
        } else {
            if ($display_error) {
                $args['status_header']['display_error'] = true;
                c\status_header(404, $args['status_header']);
            }
            return false; // Failure.
        }
    }

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
    public function get(string $file, string $dir = '', array $parents = [], array $parent_vars = []): Classes\Template
    {
        return c\di_get(Classes\Template::class, compact('dir', 'file', 'parents', 'parent_vars'));
    }
}
