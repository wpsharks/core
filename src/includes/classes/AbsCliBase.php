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
    public $command;

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

        $this->command = (object) [
            'slug'       => '',
            'class'      => '',
            'class_path' => '',
        ];
        if (!empty($GLOBALS['argv'][1])) {
            $this->command->slug = (string) $GLOBALS['argv'][1];
            $this->command->slug = strtolower($this->command->slug);
            $this->command->slug = preg_replace('/[^a-z0-9]+/', '-', $this->command->slug);
            $this->command->slug = $this->Trim($this->command->slug, '', '-');

            $this->command->class      = $this->commandClass($this->command->slug);
            $this->command->class_path = $this->commandClassPath($this->command->slug);
        }
        if ($this->command->class_path && class_exists($this->command->class_path)) {
            $this->initConfig(); // Initialize any config. values.
            $this->Dicer->get($this->command->class_path, [$this], true);
        } else {
            throw new \Exception('Unknown command: `'.$this->command->slug.'`');
            exit(1); // Error exit status.
        }
    }

    /**
     * Initialize/config.
     *
     * @since 15xxxx Initial release.
     */
    protected function initConfig()
    {
        // For extenders.
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

        return $class ? get_class().'\\'.$class : '';
    }
}
