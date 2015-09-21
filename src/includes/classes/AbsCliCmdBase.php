<?php
declare (strict_types = 1);
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
    protected $CliStream;
    protected $CliExceptions;

    /**
     * @type string Version.
     *
     * @since 15xxxx Initial release.
     */
    protected $version;

    /**
     * @type \stdClass Config.
     *
     * @since 15xxxx Initial release.
     */
    protected $config;

    /**
     * @type \stdClass Command.
     *
     * @since 15xxxx Initial release.
     */
    protected $command;

    /**
     * @type \stdClass Sub-command.
     *
     * @since 15xxxx Initial release.
     */
    protected $sub_command;

    /**
     * Version string.
     *
     * @since 15xxxx Initial release.
     *
     * @return string Version.
     */
    abstract protected function version();

    /**
     * Initialize/config.
     *
     * @since 15xxxx Initial release.
     */
    abstract protected function initConfig();

    /**
     * Sub-command aliases.
     *
     * @since 15xxxx Initial release.
     *
     * @return array Sub-command aliases.
     */
    abstract protected function subCommandAliases();

    /**
     * Available sub-commands.
     *
     * @since 15xxxx Initial release.
     *
     * @return array Available sub-commands.
     */
    abstract protected function availableSubCommands();

    /**
     * Constructor.
     *
     * @since 15xxxx Initial release.
     */
    final public function __construct()
    {
        parent::__construct();

        # Dependency injector.

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
        $this->CliStream     = $this->Dicer->get(CliStream::class);
        $this->CliExceptions = $this->Dicer->get(CliExceptions::class);

        # Only run this if we are in fact on a CLI.

        if (!$this->Cli->is() || empty($GLOBALS['argv'][0])) {
            throw new \Exception('CLI processing out of context.');
        }
        # Handle any CLI exceptions; i.e., print to STDERR.

        $this->CliExceptions->handle(); // Handle CLI exceptions.

        # Initialize additional class properties.

        $this->version     = $this->version(); // Abstract class member.
        $this->config      = new \stdClass(); // Default config. object class.
        $this->command     = (object) ['slug' => $this->commandSlug()]; // Primary.
        $this->sub_command = (object) ['slug' => '', 'class' => '', 'class_path' => ''];

        # Setup overloaded properties for read-only access.

        $this->overload(['Dicer', 'version', 'config', 'command', 'sub_command']);

        # Initialize any additional config. values.

        $this->initConfig(); // For extenders.

        # Fill the sub-command properties now; if possible.

        if (!empty($GLOBALS['argv'][1])) {
            $this->sub_command->slug       = $this->subCommandSlug('', $GLOBALS['argv'][1]);
            $this->sub_command->class      = $this->subCommandClass($this->sub_command->slug);
            $this->sub_command->class_path = $this->subCommandClassPath($this->sub_command->slug);
        }
        # Display help/info (or run sub-command); else throw an exception.

        if (!$this->sub_command->slug || !$this->sub_command->class || !$this->sub_command->class_path
                || in_array($this->sub_command->slug, ['help', 'version'], true)
                || in_array('--version', $GLOBALS['argv'], true)) {
            $this->CliStream->out($this->helpVersionInfo());
            exit(0); // Help/version/info in this case.
        } elseif (class_exists($this->sub_command->class_path)) {
            $this->Dicer->get($this->sub_command->class_path, [$this], true);
            exit(1); // Default status if sub-command fails to exit properly.
        } else {
            throw new \Exception('Unknown sub-command: `'.$this->sub_command->slug.'`');
        }
    }

    /**
     * Version help/info.
     *
     * @since 15xxxx Initial release.
     *
     * @return string Version help/info.
     */
    final public function helpVersionInfo()
    {
        $version = $this->version;
        $name    = get_class($this);
        $date    = $this->WsVersion->date($this->version);

        $info = sprintf('_*%1$s v%2$s; released %3$s*_', $name, $version, $date)."\n\n";

        $info .= '**- SYNOPSIS -**'."\n\n";

        $info .= '$ `'.$this->command->slug.' [sub-command] --help`'."\n";
        $info .= 'Call sub-commands or get help for a specific sub-command.'."\n\n";

        $info .= '**- AVAILABLE SUB-COMMANDS -**'."\n\n";

        $availableSubCommands                 = $this->availableSubCommands();
        $availableSubCommands['help|version'] = 'Display main help file.';

        foreach ($availableSubCommands as $_slug => $_desc) {
            if ($_slug && $_desc) {
                $info .= '$ `'.$this->command->slug.' '.$_slug.'` : '.$_desc."\n";
            }
        }
        unset($_slug, $_desc); // Housekeeping.

        return trim($info); // Full info sheet.
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
    final protected function commandSlug()
    {
        if (strpos(($via = (string) ini_get('session.name')), 'cli-phar-via::') === 0) {
            ini_set('session.name', 'PHPSESSID'); // Restore.
            return basename(strtolower($via)); // Basename.
        }
        return basename(strtolower((string) $GLOBALS['argv'][0]));
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
    final protected function subCommandSlug($class, $arg1 = '')
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
    final protected function subCommandClass($slug)
    {
        if (!($slug = (string) $slug)) {
            return ''; // Not possible.
        }
        $aliases = $this->subCommandAliases();
        if (!empty($aliases[$slug])) {
            $slug = $aliases[$slug];
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
    final protected function subCommandClassPath($slug)
    {
        if (!($slug = (string) $slug)) {
            return ''; // Not possible.
        }
        $aliases = $this->subCommandAliases();
        if (!empty($aliases[$slug])) {
            $slug = $aliases[$slug];
        }
        if (!($class = $this->subCommandClass($slug))) {
            return ''; // Not possible.
        }
        return get_class($this).'\\'.$class;
    }
}
