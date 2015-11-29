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
    protected $slug;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $file Template file.
     */
    public function __construct(App $App, string $file)
    {
        parent::__construct($App);

        if (!$file || !is_file($file)) {
            throw new Exception('Missing template file.');
        }
        $this->file = $file;
        $this->slug = $this->App->Utils->Name->toSlug(
            str_replace($this->App->Config->fs_paths['templates_dir'], $this->file)
        );
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
        unset($¤vars['¤this'], $¤vars['this']);
        unset($¤vars['¤defaults'], $¤vars['¤vars']);

        $¤this = $this; // Puts `$this` in the symbol table.
        // i.e., Strange magic makes it possible for `$this` to be used from
        // inside the template file also. We just need to reference it here.
        // See also: <http://stackoverflow.com/a/4994799/1219741>

        ob_start();
        require $this->file;
        return ob_get_clean();
    }
}
