<?php
/**
 * Template utilities.
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
            'protocol'                    => 'http',
            'extension'                   => 'php',
            'redirect_trailing_slash'     => true,
            'endpoint_query_var_patterns' => [],
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        $protocol                    = (string) $args['protocol'];
        $extension                   = (string) $args['extension'];
        $redirect_trailing_slash     = (bool) $args['redirect_trailing_slash'];
        $endpoint_query_var_patterns = (array) $args['endpoint_query_var_patterns'];

        if (!$protocol || !$extension) {
            return []; // Missing template path components.
        }
        if (!isset($route[0])) { // If no specific route; use current URL path.
            $route = $this->c::currentPath(); // Use current URL path.

            if ($redirect_trailing_slash && mb_substr($route, -1) === '/' && $route !== '/') {
                $current_url         = $this->c::parseUrl($this->c::currentUrl());
                $current_url['path'] = $this->c::mbRTrim($current_url['path'], '/');
                $current_url         = $this->c::unparseUrl($current_url);
                header('location: '.$current_url, true, 301);
                exit; // Stop here on redirection.
            }
        } // And now we trim leading/trailing slashes.
        $route               = $this->c::mbTrim($route, '/');
        $route               = isset($route[0]) ? $route : 'index';
        $endpoint_query_vars = []; // Initialize.

        foreach ($endpoint_query_var_patterns as $_endpoint_query_var_pattern => $_endpoint_route) {
            if (!preg_match($_endpoint_query_var_pattern, $route, $_m)) {
                continue; // Only if pattern matches.
            } elseif (!$_endpoint_route) {
                continue; // Must have.
            }
            foreach ($_m as $_key => $_value) {
                if (!is_string($_key) || !$_key || !isset($_value[0])) {
                    continue; // Skip this one.
                } // Only string keys that have a value.

                $endpoint_query_vars[urldecode($_key)] = urldecode($_value);
                $_endpoint_route                       = str_replace('%%'.$_key.'%%', $_value, $_endpoint_route);
            } // unset($_key, $_value);
            $route = $this->c::mbTrim(preg_replace($_endpoint_query_var_pattern, $_endpoint_route, $route), '/');
            break; // Stop; i.e., only one pattern can match.
        } // unset($_endpoint_query_var_pattern, $_endpoint_route, $_m);

        $template        = $this->locate($protocol.'/routes/'.$route.'.'.$extension, $dir);
        return $template = $template ? array_merge($template, ['vars' => compact('endpoint_query_vars')]) : [];
    }

    /**
     * Loads template for a route.
     *
     * @since 160118 Router templates.
     *
     * @param array $args Behavioral args.
     *
     * @return bool True if the route was successfully loaded.
     */
    public function loadRoute(array $args = []): bool
    {
        $default_args = [
            'route' => '', 'dir' => '',
            // See: {@link locateRoute()}

            'protocol'                    => 'http',
            'extension'                   => 'php',
            'redirect_trailing_slash'     => true,
            'endpoint_query_var_patterns' => [],

            'display_error'      => true,
            'display_error_page' => 'default',
            // See: {@link c::statusHeader()}
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        if (($template = $this->locateRoute($args['route'], $args['dir'], $args))) {
            echo $this->get($template['file'], $template['dir'])->parse($template['vars']);
            return true; // Loaded successfully.
        }
        if ($args['display_error']) {
            $this->c::statusHeader(404, $args);
        }
        return false; // Failure.
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
     * @return Classes\Core\Template Template instance.
     */
    public function get(string $file, string $dir = '', array $parents = [], array $parent_vars = []): Classes\Core\Template
    {
        return $this->App->Di->get(Classes\Core\Template::class, compact('dir', 'file', 'parents', 'parent_vars'));
    }
}
