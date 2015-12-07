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
    protected $current_vars;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $file    Template file.
     * @param array  $parents Parent template files.
     */
    public function __construct(App $App, string $file, array $parents = [])
    {
        parent::__construct($App);

        if (!$file || !is_file($file)) {
            throw new Exception('Missing template file.');
        }
        $this->file         = $file;
        $this->parents      = $parents;
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
    public function setVars(array $defaults, array ...$vars): array
    {
        return $this->current_vars = array_replace_recursive($defaults, ...$vars);
    }

    /**
     * Include a child template.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $file Relative to templates dir.
     * @param string $dir  From a specific directory?
     *
     * @return string Parsed template contents.
     */
    public function inc(string $file, string $dir = ''): string
    {
        if (!preg_match('/\.inc\.[^.]+$/u', $file)) {
            throw new Exception('Includes must end w/ `.inc.[ext]` please.');
        }
        $Template = $this->Utils->Template->get($file, $dir, array_merge($this->parents, [$this->file]));

        return $Template->parse($this->current_vars);
    }
}
