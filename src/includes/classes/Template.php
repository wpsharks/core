<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Classes\AppUtils;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Template.
 *
 * @since 150424 Initial release.
 */
class Template extends AbsBase
{
    protected $file;
    protected $parents;
    protected $parent_vars;
    protected $current_vars;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $file        Template file.
     * @param array  $parents     Parent template files.
     * @param array  $parent_vars Parent template vars.
     */
    public function __construct(
        App $App,
        string $file,
        array $parents = [],
        array $parent_vars = []
    ) {
        parent::__construct($App);

        if (!$file || !is_file($file)) {
            throw new Exception('Missing template file.');
        }
        $this->file         = $file;
        $this->parents      = $parents;
        $this->parent_vars  = $parent_vars;
        $this->current_vars = [];
    }

    /**
     * Parse template.
     *
     * @since 15xxxx Initial release.
     *
     * @param array $¤vars Template vars.
     *
     * @return string Parsed template contents.
     */
    public function parse(array $¤vars = []): string
    {
        $¤this = $this; // `$this` in symbol table.
        // ↑ Strange magic makes it possible for `$this` to be used from
        // inside the template file also. We just need to reference it here.
        // See: <http://stackoverflow.com/a/4994799/1219741>

        unset($¤this, $¤vars['¤this'], $¤vars['this']);
        unset($¤vars['¤defaults'], $¤vars['¤vars']);
        $this->current_vars = $¤vars;

        ob_start();
        require $this->file;
        return ob_get_clean();
    }

    /**
     * Set current vars.
     *
     * @since 15xxxx Initial release.
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
     * @since 15xxxx Initial release.
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
        $Template    = $this->Utils->Template->get($file, $dir, $parents, $parent_vars);

        if (isset($this->current_vars[$file])) {
            $vars = array_replace_recursive($this->current_vars[$file], $vars);
        }
        return $Template->parse($vars);
    }

    /**
     * Has a parent template?
     *
     * @since 15xxxx Initial release.
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
     * @since 15xxxx Initial release.
     *
     * @param string $file Relative to templates dir.
     *
     * @return array Parent template vars.
     */
    protected function parentVars(string $file): array
    {
        return $this->parent_vars[$file] ?? [];
    }

    /**
     * Checked?
     *
     * @since 15xxxx Initial release.
     *
     * @param mixed $a Input variable a.
     * @param mixed $b Input variable b.
     *
     * @return string ` checked` if true.
     */
    protected function checked($a, $b)
    {
        return (string) $a === (string) $b ? ' checked' : '';
    }

    /**
     * Selected?
     *
     * @since 15xxxx Initial release.
     *
     * @param mixed $a Input variable a.
     * @param mixed $b Input variable b.
     *
     * @return string ` selected` if true.
     */
    protected function selected($a, $b)
    {
        return (string) $a === (string) $b ? ' selected' : '';
    }
}
