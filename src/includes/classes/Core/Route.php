<?php
/**
 * Route.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Route.
 *
 * @since 161008 Route.
 */
class Route extends Classes\Core\Base\Core
{
    /**
     * Directory.
     *
     * @since 161008
     *
     * @var string
     */
    protected $dir;

    /**
     * File path.
     *
     * @since 161008
     *
     * @var string
     */
    protected $file;

    /**
     * File ext.
     *
     * @since 161008
     *
     * @var string
     */
    protected $ext;

    /**
     * Path.
     *
     * @since 161008
     *
     * @var string
     */
    protected $path;

    /**
     * Rewrite query vars.
     *
     * @since 161008
     *
     * @var array
     */
    protected $rewrite_query_vars;

    /**
     * Query vars.
     *
     * @since 161008
     *
     * @var array
     */
    protected $query_vars;

    /**
     * Variables.
     *
     * @since 161008
     *
     * @var array
     */
    protected $vars;

    /**
     * Parse counter.
     *
     * @since 161008
     *
     * @var int
     */
    protected $parsed;

    /**
     * Class constructor.
     *
     * @since 161008 Route.
     *
     * @param Classes\App $App  Instance of App.
     * @param array       $args Route instance args.
     */
    public function __construct(Classes\App $App, array $args)
    {
        parent::__construct($App);

        $default_args = [
            'dir'                => '',
            'file'               => '',
            'ext'                => '',
            'route'              => '',
            'rewrite_query_vars' => [],
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        $this->dir                = (string) $args['dir'];
        $this->file               = (string) $args['file'];
        $this->ext                = (string) $args['ext'];
        $this->path               = (string) $args['route'];
        $this->rewrite_query_vars = (array) $args['rewrite_query_vars'];
        $this->query_vars         = [];
        $this->vars               = [];
        $this->parsed             = 0;

        $this->setAdditionalProps();
    }

    /**
     * Reserved for extenders.
     *
     * @since 161008 Route.
     */
    protected function setAdditionalProps()
    {
        // Reserved for extenders.
    }

    /**
     * Route path.
     *
     * @since 160926 Initial release.
     *
     * @return string Route path.
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * Route as slug.
     *
     * @since 160926 Initial release.
     *
     * @return string Route as slug.
     */
    public function slug(): string
    {
        return $this->c::nameToSlug($this->path);
    }

    /**
     * Route matches?
     *
     * @since 161008 Route.
     *
     * @param string $route Route.
     *
     * @return bool True if route matches.
     */
    public function is(string $route): bool
    {
        $this->path === $route;
    }

    /**
     * Route file.
     *
     * @since 160926 Initial release.
     *
     * @return string Route file.
     */
    public function file(): string
    {
        return $this->file;
    }

    /**
     * Route file slug.
     *
     * @since 160926 Initial release.
     *
     * @return string Route file slug.
     */
    public function fileSlug(): string
    {
        return $this->c::nameToSlug($this->file());
    }

    /**
     * File matches?
     *
     * @since 161008 Route.
     *
     * @param string $file File.
     *
     * @return bool True if file matches.
     */
    public function fileIs(string $file): bool
    {
        $this->file === $file;
    }

    /**
     * Parse route.
     *
     * @since 161008 Route.
     *
     * @param string $key Query var key.
     *
     * @return string Parsed route contents.
     */
    public function queryVar(string $key): string
    {
        if (isset($this->query_vars[$key])) {
            return $this->query_vars[$key];
        }
        $value = ''; // Initialize.

        if (isset($_REQUEST[$key])) {
            $value = (string) $_REQUEST[$key];
            $value = $this->c::unslash($value);
            $value = $this->c::mbTrim($value);
        } elseif (isset($this->rewrite_query_vars[$key])) {
            $value = $this->rewrite_query_vars[$key];
        }
        return $this->query_vars[$key] = $value;
    }

    /**
     * Parse route.
     *
     * @since 161008 Route.
     *
     * @param array $vars Route vars.
     *
     * @return string Parsed route contents.
     */
    public function parse(array $vars = []): string
    {
        if ($this->parsed) {
            return ''; // Parse once only.
        }
        ++$this->parsed; // Parsing now.

        if ($this->ext === 'php') {
            $_this = $this; // `$this` in symbol table.
            // ↑ Strange magic makes it possible for `$this` to be used from
            // inside the route file also. We just need to reference it here.
            // See: <http://stackoverflow.com/a/4994799/1219741>

            unset($_this, $vars['this']); // Avoid conflicts.
            $this->vars = $vars; // Set current route variables.
            unset($vars); // Don't include as a part of route variables.

            extract($this->vars); // Extract for route.

            ob_start(); // Output buffer.
            require $this->dir.'/'.$this->file;
            return ob_get_clean();
        } else {
            return file_get_contents($this->dir.'/'.$this->file);
        }
    }

    /**
     * Set route vars.
     *
     * @since 161008 Route.
     *
     * @param array $defaults Default vars.
     * @param array ...$vars Variadic route vars.
     *
     * @return array New route vars.
     */
    protected function setVars(array $defaults, array ...$vars): array
    {
        return $this->vars = array_replace_recursive($defaults, ...$vars);
    }
}
