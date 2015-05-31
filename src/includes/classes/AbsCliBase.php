<?php
namespace WebSharks\Core\Classes;

use WebSharks\Dicer\Core as Dicer;

/**
 * CLI primary command base.
 *
 * @since 15xxxx Initial release.
 */
abstract class AbsCliBase extends AbsBase
{
    public $Dicer;
    protected $Trim;
    protected $CliExceptions;

    /**
     * @type \stdClass Command.
     *
     * @since 15xxxx Initial release.
     */
    public $Command;

    /**
     * Constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        parent::__construct();

        $this->Dicer         = new Dicer(['shared' => true]);
        $this->Trim          = $this->Dicer->get(Trim::class);
        $this->CliExceptions = $this->Dicer->get(CliExceptions::class);

        $this->CliExceptions->handle(); // Handle CLI exceptions.

        $this->Command = (object) [
            'slug'       => '',
            'class'      => '',
            'class_path' => '',
        ];
        if (!empty($GLOBALS['argv'][1])) {
            $this->Command->slug = (string) $GLOBALS['argv'][1];
            $this->Command->slug = strtolower($this->Command->slug);
            $this->Command->slug = preg_replace('/[^a-z0-9]+/', '-', $this->Command->slug);
            $this->Command->slug = $this->Trim($this->Command->slug, '', '-');

            $this->Command->class      = $this->commandClass($this->Command->slug);
            $this->Command->class_path = $this->commandClassPath($this->Command->slug);
        }
        if ($this->Command->class_path && class_exists($this->Command->class_path)) {
            $this->Dicer->get($this->Command->class_path, [$this], true);
        } else {
            throw new \Exception('Unknown command: `'.$this->Command->slug.'`');
            exit(1); // Error exit status.
        }
    }

    /**
     * Class slug from path.
     *
     * @since 15xxxx Initial release.
     */
    public function commandSlug($class)
    {
        $class = (string) $class;
        $class = basename(str_replace('\\', '/', $class));

        $slug = preg_replace('/([A-Z])/', '-${1}', $class);
        $slug = $this->Trim(strtolower($slug), '', '-');
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);

        return $slug;
    }

    /**
     * Class path from slug.
     *
     * @since 15xxxx Initial release.
     */
    public function commandClass($slug)
    {
        $slug  = (string) $slug;
        $parts = preg_split('/\-/', $slug, null, PREG_SPLIT_NO_EMPTY);
        $parts = array_map('ucfirst', array_map('strtolower', $parts));
        $class = implode('', $parts); // e.g., CommandClass

        return $class;
    }

    /**
     * Class path from slug.
     *
     * @since 15xxxx Initial release.
     */
    public function commandClassPath($slug)
    {
        $slug  = (string) $slug;
        $class = $this->commandClass($slug);

        return $class ? __NAMESPACE__.'\\Commands\\'.$class : '';
    }
}
