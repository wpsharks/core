<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
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
     * @type string
     */
    protected $dir;

    /**
     * File path.
     *
     * @since 150424
     *
     * @type string
     */
    protected $file;

    /**
     * File ext.
     *
     * @since 150424
     *
     * @type string
     */
    protected $ext;

    /**
     * Parent files.
     *
     * @since 150424
     *
     * @type array
     */
    protected $parents;

    /**
     * Parent vars.
     *
     * @since 150424
     *
     * @type array
     */
    protected $parent_vars;

    /**
     * Current vars.
     *
     * @since 150424
     *
     * @type array
     */
    protected $current_vars;

    /**
     * Class constructor.
     *
     * @since 150424 Initial release.
     *
     * @param Classes\App $App         Instance of App.
     * @param string      $dir         Template dir.
     * @param string      $file        Template file.
     * @param array       $parents     Parent template files.
     * @param array       $parent_vars Parent template vars.
     */
    public function __construct(Classes\App $App, string $dir, string $file, array $parents = [], array $parent_vars = [])
    {
        parent::__construct($App);

        if (!($template = $this->c::locateTemplate($file, $dir))) {
            throw $this->c::issue(sprintf('Missing template: `%1$s`.', $dir.'/'.$file));
        }
        $this->dir  = $template['dir'];
        $this->file = $template['file'];
        $this->ext  = $template['ext'];

        $this->parents      = $parents;
        $this->parent_vars  = $parent_vars;
        $this->current_vars = [];

        $this->setAdditionalProps();
    }

    /**
     * Additional props (for extenders).
     *
     * @since 160715 Initial release.
     */
    protected function setAdditionalProps()
    {
        // For extenders only.
    }

    /**
     * Parse template.
     *
     * @since 150424 Initial release.
     *
     * @param array $¤vars Template vars.
     *
     * @return string Parsed template contents.
     */
    public function parse(array $¤vars = []): string
    {
        if ($this->ext === 'php') {
            $¤this = $this; // `$this` in symbol table.
            // ↑ Strange magic makes it possible for `$this` to be used from
            // inside the template file also. We just need to reference it here.
            // See: <http://stackoverflow.com/a/4994799/1219741>

            unset($¤this, $¤vars['¤this'], $¤vars['this']);
            unset($¤vars['¤defaults'], $¤vars['¤vars']);

            $this->current_vars = $¤vars;

            ob_start(); // Output buffer.
            require $this->dir.'/'.$this->file;
            return ob_get_clean();
        } else {
            return file_get_contents($this->dir.'/'.$this->file);
        }
    }

    /**
     * Set current vars.
     *
     * @since 150424 Initial release.
     *
     * @param array $defaults Default vars.
     * @param array ...$vars Template vars.
     *
     * @return array Current vars.
     */
    protected function setVars(array $defaults, array ...$vars): array
    {
        return $this->current_vars = array_replace_recursive($defaults, ...$vars);
    }

    /**
     * Get a child template.
     *
     * @since 150424 Initial release.
     *
     * @param string $file Relative to templates dir.
     * @param array  $vars Template vars for the include.
     * @param string $dir  From a specific directory?
     *
     * @return string Parsed template contents.
     */
    protected function get(string $file, array $vars = [], string $dir = ''): string
    {
        $parents     = array_merge($this->parents, [$this->file]);
        $parent_vars = array_merge($this->parent_vars, [$this->file => &$this->current_vars]);
        $Template    = $this->c::getTemplate($file, $dir, $parents, $parent_vars);
        /*
         * NOTE: Here, we are walking through each of the parents in reverse order.
         * Merging variables from the closest ancestor first, then it's parent, and so on.
         *
         * Only file-specific variables from parents are merged w/ the variables for 'this' template file.
         * And, we always give variables for 'this' template precedence over those defined by ancestors.
         *
         * The most confusing part of this routine has to do with the order in which the merging takes place.
         * Each time a merge occurs, it is merging the 'current' variables for 'this' template, with those from an ancestor.
         *
         * The trick is, each time a merge occurs, the 'current' variables will include those from the previous ancestor.
         * The effect is that the 'current' template take precedence over all others, and then each closest parent takes
         * precedence over the ones before it. By going in reverse, we're filling the vars needed for the next merge.
         */
        foreach (array_reverse($parent_vars, true) as $_parent_file => $_parent_vars) {
            if (isset($_parent_vars[$file]) && is_array($_parent_vars[$file])) {
                $vars = array_replace_recursive($_parent_vars[$file], $vars);
            } // Merge those from parents who have file-specific vars.
        } // unset($_parent_file, $_parent_vars); // Housekeeping.

        return $Template->parse($vars);
    }

    /**
     * Has a parent template?
     *
     * @since 150424 Initial release.
     *
     * @param string $file Relative to templates dir.
     *
     * @return bool True if child has the parent template file.
     */
    protected function hasParent(string $file): bool
    {
        return in_array($file, $this->parents, true);
    }

    /**
     * Parent template vars.
     *
     * @since 150424 Initial release.
     *
     * @param string $file Relative to templates dir.
     *
     * @return array Parent template vars.
     */
    protected function parentVars(string $file): array
    {
        return $this->parent_vars[$file] ?? [];
    }
}
