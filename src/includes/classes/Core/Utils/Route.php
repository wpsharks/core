<?php
/**
 * Route utilities.
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
 * Route utilities.
 *
 * @since 161008 Route utilities.
 */
class Route extends Classes\Core\Base\Core
{
    /**
     * Loads route file.
     *
     * @since 161008 Route utilities.
     *
     * @param array $args Behavioral args.
     *
     * @return bool True if route was loaded.
     */
    public function load(array $args = []): bool
    {
        $default_args = [
            'path' => '',
            'dir'  => '',
            'vars' => [],

            'rewrites'                => [],
            'protocol'                => 'http',
            'extension'               => 'php',
            'redirect_trailing_slash' => true,
            // See: {@link resolve()}

            'display_error'      => true,
            'display_error_page' => 'default',
            // See: {@link c::statusHeader()}
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        $args['path'] = (string) $args['path'];
        $args['dir']  = (string) $args['dir'];
        $args['vars'] = (array) $args['vars'];

        if (($resolved = $this->resolve($args['path'], $args['dir'], $args))) {
            echo $this->get($resolved)->parse($args['vars']);
            return true; // Loaded successfully.
        }
        if ($args['display_error']) {
            $this->c::statusHeader(404, $args);
        }
        return false; // Failure.
    }

    /**
     * Resolves a route.
     *
     * @since 161008 Route utilities.
     *
     * @param string $path A URL path/route.
     * @param string $dir  From a specific directory?
     * @param array  $args Any additional behavioral args.
     *
     * @return array `[dir, file, ext, path, rewrite_query_vars]`.
     */
    public function resolve(string $path = '', string $dir = '', array $args = []): array
    {
        $default_args = [
            'rewrites'                => [],
            'protocol'                => 'http',
            'extension'               => 'php',
            'redirect_trailing_slash' => true,
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        $rewrites                = (array) $args['rewrites'];
        $protocol                = (string) $args['protocol'];
        $extension               = (string) $args['extension'];
        $redirect_trailing_slash = (bool) $args['redirect_trailing_slash'];

        if (!$protocol || !$extension) {
            return []; // Missing components.
        }
        if (!isset($path[0])) { // If no specific route; use current URL path.
            $path = $this->c::currentPath(); // Use current URL path.

            if ($redirect_trailing_slash && mb_substr($path, -1) === '/' && $path !== '/') {
                $current_url         = $this->c::parseUrl($this->c::currentUrl());
                $current_url['path'] = $this->c::mbRTrim($current_url['path'], '/');
                $current_url         = $this->c::unparseUrl($current_url);
                header('location: '.$current_url, true, 301);
                exit; // Stop here on redirection.
            }
        } // Trim leading/trailing slashes.
        $path               = $this->c::mbTrim($path, '/');
        $path               = isset($path[0]) ? $path : 'index';
        $rewrite_query_vars = []; // Initialize query vars.

        foreach ($rewrites as $_rewrite_pattern => $_rewrite_path) {
            if (!preg_match($_rewrite_pattern, $path, $_m)) {
                continue; // Only if pattern matches.
            } elseif (!$_rewrite_path) {
                continue; // Must have.
            }
            $_rewrite_path_rcs = mb_strpos($_rewrite_path, '%%') !== false;

            foreach ($_m as $_key => $_value) {
                if (!is_string($_key) || !$_key || !isset($_value[0])) {
                    continue; // Skip this one.
                } // Only string keys w/ a value.

                if ($_rewrite_path_rcs) { // If applicable.
                    $_rewrite_path = str_replace('%%'.$_key.'%%', $_value, $_rewrite_path);
                } // This allows replacement codes in rewritten paths.
                $rewrite_query_vars[urldecode($_key)] = urldecode($_value);
            } // unset($_key, $_value); // Housekeeping.

            if ($_rewrite_path_rcs && mb_strpos($_rewrite_path, '%%') !== false) {
                $_rewrite_path = preg_replace('/%%.+?%%/u', '', $_rewrite_path);
                $_rewrite_path = preg_replace('/\/{2,}/u', '/', $_rewrite_path);
            }
            $path = $this->c::mbTrim($_rewrite_path, '/');
            break; // Only one pattern can match; i.e., the first match wins.
        } // unset($_rewrite_pattern, $_rewrite_path, $_rewrite_path_contains_rcs, $_m);

        $location        = $path ? $this->locate($protocol.'/'.$path.'.'.$extension, $dir) : [];
        return $resolved = $location ? array_merge($location, compact('path', 'rewrite_query_vars')) : [];
    }

    /**
     * Locates a route file.
     *
     * @since 161008 Route utilities.
     *
     * @param string $file Relative to routes dir.
     * @param string $dir  From a specific directory?
     *
     * @return array Route `[dir, file, ext]`.
     */
    public function locate(string $file, string $dir = ''): array
    {
        $is_file = false; // Initialize.
        $file    = $this->c::mbTrim($file, '/');
        $dir     = $this->c::mbRTrim($dir, '/');
        $dir     = $dir ?: $this->App->Config->©fs_paths['©routes_dir'];

        if ($dir === 'core') {
            if ($this->App->Parent) {
                return $this->App->Parent->Utils->©Route->locate($file, $dir);
            } else { // Use core config option.
                $dir = $this->App->Config->©fs_paths['©routes_dir'];
            }
        } elseif ($dir === 'parent' || ($dir && $file && !($is_file = is_file($dir.'/'.$file)))) {
            if ($this->App->Parent) {
                return $this->App->Parent->Utils->©Route->locate($file);
            } else { // Else go with what we have.
                $dir = $this->App->Config->©fs_paths['©routes_dir'];
            }
        }
        if ($dir && $file && !$is_file && !($is_file = is_file($dir.'/'.$file))) {
            $dir = dirname(__FILE__, 4).'/routes'; // WS core routes.
        }
        if ($dir && $file && ($is_file || ($is_file = is_file($dir.'/'.$file)))) {
            if (preg_match('/\/\.|\.\/|\.\./u', $this->c::normalizeDirPath($dir.'/'.$file))) {
                throw $this->c::issue(sprintf('Insecure route path: `%1$s`.', $dir.'/'.$file));
            }
            return ['dir' => $dir, 'file' => $file, 'ext' => $this->c::fileExt($file)];
        }
        return []; // Unable to locate.
    }

    /**
     * Gets route.
     *
     * @since 161008 Route utilities.
     *
     * @param array $args Route instance args.
     *
     * @return Classes\Core\Route Route instance.
     */
    public function get(array $args = []): Classes\Core\Route
    {
        return $this->App->Di->get(Classes\Core\Route::class, compact('args'));
    }
}
