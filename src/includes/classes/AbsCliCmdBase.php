<?php
namespace WebSharks\Core\Classes;

use WebSharks\Dicer\Core as Dicer;
use GetOptionKit\OptionCollection;

/**
 * CLI primary command base.
 *
 * @since 15xxxx Initial release.
 */
abstract class AbsCliCmdBase extends AbsBase
{
    protected $Dicer;
    protected $Cli;
    protected $Trim;
    protected $Coalesce;
    protected $WsVersion;
    protected $CliExceptions;

    /**
     * @type string Version.
     *
     * @since 15xxxx Initial release.
     */
    protected $version;

    /**
     * @type string Primary command.
     *
     * @since 15xxxx Initial release.
     */
    protected $command_slug;

    /**
     * @type \stdClass Sub-command.
     *
     * @since 15xxxx Initial release.
     */
    protected $sub_command;

    /**
     * Constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        parent::__construct();

        $this->Dicer = new Dicer([
            'shared'        => true,
            'new_instances' => [
                CliOpts::class,
                OptionCollection::class,
            ],
        ]);
        $this->Cli           = $this->Dicer->get(Cli::class);
        $this->Trim          = $this->Dicer->get(Trim::class);
        $this->Coalesce      = $this->Dicer->get(Coalesce::class);
        $this->WsVersion     = $this->Dicer->get(WsVersion::class);
        $this->CliExceptions = $this->Dicer->get(CliExceptions::class);

        if (!$this->Cli->is() || empty($GLOBALS['argv'][0])) {
            throw new \Exception('CLI processing out of context.');
        }
        $this->CliExceptions->handle(); // Handle CLI exceptions.

        $this->version      = (string) $this->version; // Force string.
        $this->command_slug = basename(strtolower((string) $GLOBALS['argv'][0]));
        $this->sub_command  = (object) ['slug' => '', 'class' => '', 'class_path' => ''];

        if (!empty($GLOBALS['argv'][1])) {
            $this->sub_command->slug       = $this->subCommandSlug('', $GLOBALS['argv'][1]);
            $this->sub_command->class      = $this->subCommandClass($this->sub_command->slug);
            $this->sub_command->class_path = $this->subCommandClassPath($this->sub_command->slug);
        }
        if (!$this->sub_command->slug || !$this->sub_command->class || !$this->sub_command->class_path
                || in_array($this->sub_command->slug, ['help', 'version'], true)
                || in_array('--version', $GLOBALS['argv'], true)) {
            $this->CliStream->out($this->helpVersionInfo());
            exit(0); // Help/version/info in this case.
        } elseif (class_exists($this->sub_command->class_path)) {
            $this->overload['Dicer']        = $this->Dicer;
            $this->overload['version']      = $this->version;
            $this->overload['command_slug'] = $this->command_slug;
            $this->overload['sub_command']  = $this->sub_command;
            $this->initConfig(); // Initialize any config. values.
            $this->Dicer->get($this->sub_command->class_path, [$this], true);
            exit(0); // Success unless the sub-command says otherwise.
        } else {
            throw new \Exception('Unknown sub-command: `'.$this->sub_command->slug.'`');
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
     * Available sub-commands.
     *
     * @since 15xxxx Initial release.
     *
     * @return array Available sub-commands.
     */
    public function availableSubCommands()
    {
        return []; // For extenders.
    }

    /**
     * Version help/info.
     *
     * @since 15xxxx Initial release.
     *
     * @return string Version help/info.
     */
    public function helpVersionInfo()
    {
        $version = $this->version;
        $name    = get_class($this);
        $date    = $this->WsVersion->date($this->version);

        $info = sprintf('%1$s v%2$s; released %3$s.', $name, $version, $date)."\n";
        $info .= 'Usage: `'.$this->command_slug.' [sub-command] --help`'."\n";

        $info .= '--- Available Sub-Commands: ---'."\n";

        $availableSubCommands                 = $this->availableSubCommands();
        $availableSubCommands['help|version'] = 'Display main help file.';

        foreach ($availableSubCommands as $_slug => $_desc) {
            if ($_slug && $_desc) {
                $info .= '`'.$_slug.'`: '.$_desc."\n";
            }
        }
        unset($_slug, $_desc); // Housekeeping.

        return $info; // Full info sheet.
    }

    /**
     * Sub-command class|arg[1] to slug conversion.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $class Input class name.
     * @param string $arg1  Or, an input arg[1].
     *
     * @return string Slug name; e.g., `sub-command`.
     */
    public function subCommandSlug($class, $arg1 = '')
    {
        if (!($class = (string) $class) && !($arg1 = (string) $arg1)) {
            return ''; // Not possible.
        }
        $slug = $this->Coalesce->notEmpty($class, $arg1);
        $slug = $class ? basename(str_replace('\\', '/', $slug)) : $slug;
        $slug = $class ? preg_replace('/([A-Z])/', '-${1}', $slug) : $slug;
        $slug = preg_replace('/[^a-z0-9]+/', '-', strtolower($slug));
        $slug = $this->Trim($slug, '', '-');

        return $slug; // All lowercase w/ dashes.
    }

    /**
     * Sub-command slug to class conversion.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $slug Input slug to convert.
     *
     * @return string Class name; e.g., `SubCommand`.
     */
    public function subCommandClass($slug)
    {
        if (!($slug = (string) $slug)) {
            return ''; // Not possible.
        }
        $parts = preg_split('/\-/', $slug, null, PREG_SPLIT_NO_EMPTY);
        $parts = array_map('ucfirst', array_map('strtolower', $parts));
        $class = implode('', $parts); // e.g., `SubCommand`.

        return $class; // Now in TitleCase.
    }

    /**
     * Sub-command class path from slug.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $slug Input slug to convert.
     *
     * @return string Class path; e.g., `Namespace\Primary\SubCommand`.
     */
    public function subCommandClassPath($slug)
    {
        if (!($slug = (string) $slug)) {
            return ''; // Not possible.
        }
        if (!($class = $this->subCommandClass($slug))) {
            return ''; // Not possible.
        }
        return get_class($this).'\\'.$class;
    }
}
