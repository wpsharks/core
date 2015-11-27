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
    }

    /**
     * Parse template.
     *
     * @since 15xxxx Initial release.
     *
     * @param array $vars Template vars.
     *
     * @return string Parsed template contents.
     */
    public function parse(array $vars = []): string
    {
        extract($vars);
        $template = $this;

        ob_start();
        require $this->file;
        return ob_get_clean();
    }
}
