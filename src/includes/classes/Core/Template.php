<?php
/**
 * Template.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Template.
 *
 * @since 150424 Initial release.
 */
class Template extends Classes\Core\Base\Core
{
    /**
     * Directory.
     *
     * @since 150424
     *
     * @var string
     */
    protected $dir;

    /**
     * File path.
     *
     * @since 150424
     *
     * @var string
     */
    protected $file;

    /**
     * File ext.
     *
     * @since 150424
     *
     * @var string
     */
    protected $ext;

    /**
     * Parent files.
     *
     * @since 150424
     *
     * @var array
     */
    protected $parents;

    /**
     * Parent vars.
     *
     * @since 150424
     *
     * @var array
     */
    protected $parent_vars;

    /**
     * Variables.
     *
     * @since 150424
     *
     * @var array
     */
    protected $vars;

    /**
     * Route.
     *
     * @since 161008
     *
     * @var null|Classes\Core\Route
     */
    protected $Route;

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
     * @since 150424 Initial release.
     *
     * @param Classes\App $App  Instance of App.
     * @param string      $dir  Template file directory.
     * @param string      $file Relative to directory.
     * @param array       $args Additional args.
     */
    public function __construct(Classes\App $App, string $dir, string $file, array $args = [])
    {
        parent::__construct($App);

        if (!($template = $this->c::locateTemplate($file, $dir))) {
            throw $this->c::issue(sprintf('Missing template: `%1$s`.', $dir.'/'.$file));
        }
        $default_args = [
            'parents'     => [],
            'parent_vars' => [],
            'Route'       => null,
        ];
        $args += $default_args;

        $this->dir         = $template['dir'];
        $this->file        = $template['file'];
        $this->ext         = $template['ext'];
        $this->parents     = (array) $args['parents'];
        $this->parent_vars = (array) $args['parent_vars'];
        $this->vars        = []; // Initialize.
        $this->Route       = $args['Route'];
        $this->parsed      = 0;

        $this->setAdditionalProps();
    }

    /**
     * Reserved for extenders.
     *
     * @since 160715 Initial release.
     */
    protected function setAdditionalProps()
    {
        // Reserved for extenders.
    }

    /**
     * Parse template.
     *
     * @since 150424 Initial release.
     *
     * @param array $vars Template vars.
     *
     * @return string Parsed template contents.
     */
    public function parse(array $vars = []): string
    {
        ++$this->parsed; // Parsing now.

        if ($this->ext === 'php') {
            $_this = $this; // `$this` in symbol table.
            // ↑ Strange magic makes it possible for `$this` to be used from
            // inside the template file also. We just need to reference it here.
            // See: <http://stackoverflow.com/a/4994799/1219741>

            unset($_this, $vars['this']); // Avoid conflicts.
            $this->vars = $vars; // Set current template variables.
            unset($vars); // Don't include as a part of template variables.

            extract($this->vars); // Extract for template.

            ob_start(); // Output buffer.
            require $this->dir.'/'.$this->file;
            return ob_get_clean();
        } else {
            return file_get_contents($this->dir.'/'.$this->file);
        }
    }

    /**
     * Set template vars.
     *
     * @since 150424 Initial release.
     *
     * @param array $defaults Default vars.
     * @param array ...$vars Variadic template vars.
     *
     * @return array New template vars.
     */
    protected function setVars(array $defaults, array ...$vars): array
    {
        return $this->vars = array_replace_recursive($defaults, $this->vars, ...$vars);
    }

    /**
     * Has a parent?
     *
     * @since 150424 Initial release.
     *
     * @param string|null $file Template file.
     *
     * @return bool True if child has a parent.
     */
    protected function hasParent(string $file = null): bool
    {
        if (isset($file)) {
            return in_array($file, $this->parents, true);
        }
        return !empty($this->parents);
    }

    /**
     * Parent template vars.
     *
     * @since 150424 Initial release.
     *
     * @param string|null $file Template file.
     *
     * @return array Parent template vars.
     */
    protected function parentVars(string $file = null): array
    {
        if (isset($file)) {
            return $this->parent_vars[$file] ?? [];
        }
        $closest_ancestor_vars = end($this->parent_vars);
        return $parent_vars    = $closest_ancestor_vars ?: [];
    }

    /**
     * Main template file.
     *
     * @since 160926 Initial release.
     *
     * @return string Main template file.
     */
    protected function mainFile(): string
    {
        if ($this->parents) {
            return $this->parents[0];
        }
        return $this->file;
    }

    /**
     * Main template file slug.
     *
     * @since 160926 Initial release.
     *
     * @return string Main template file slug.
     */
    protected function mainFileSlug(): string
    {
        return $this->c::nameToSlug($this->mainFile());
    }

    /**
     * Main template file is?
     *
     * @since 160926 Initial release.
     *
     * @param string $file File to match.
     *
     * @return bool True if main template file is.
     */
    protected function mainFileIs(string $file): bool
    {
        return $this->mainFile() === $file;
    }

    /**
     * Main template file matches?
     *
     * @since 160926 Initial release.
     *
     * @param string $regex Regex to match.
     *
     * @return bool True if main template file matches.
     */
    protected function mainFileMatches(string $regex): bool
    {
        return (bool) preg_match($regex, $this->mainFile());
    }

    /**
     * Get a child template.
     *
     * @since 150424 Initial release.
     *
     * @param string $new_child_file Relative to templates dir.
     * @param array  $new_child_vars Template vars for the include.
     * @param string $new_child_dir  From a specific directory?
     *
     * @return string Parsed template contents for child template.
     */
    protected function get(string $new_child_file, array $new_child_vars = [], string $new_child_dir = ''): string
    {
        $new_child_parents     = array_merge($this->parents, [$this->file]);
        $new_child_parent_vars = array_merge($this->parent_vars, [$this->file => &$this->vars]);
        $new_child_Template    = $this->c::getTemplate($new_child_file, $new_child_dir, [
            'parents'     => $new_child_parents,
            'parent_vars' => $new_child_parent_vars,
            'Route'       => $this->Route,
        ]);
        // Variables from the closest ancestors take precedence over further/older ancestors.
        // File-specific variables in those ancestors take precedence over those that aren't file-specific.

        foreach (array_reverse($new_child_parent_vars, true) as $_parent_file => $_parent_vars) {
            if (isset($_parent_vars[$new_child_file]) && is_array($_parent_vars[$new_child_file])) {
                $new_child_vars = array_replace_recursive($_parent_vars[$new_child_file], $new_child_vars);
            }
            $_parent_vars_not_file_specific = array_diff_key($_parent_vars, $new_child_parent_vars + [$new_child_file => 0]);
            $new_child_vars                 = array_replace_recursive($_parent_vars_not_file_specific, $new_child_vars);
        } // unset($_parent_file, $_parent_vars, $_parent_vars_not_file_specific); // Housekeeping.

        return $new_child_Template->parse($new_child_vars);
    }
}
