<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * CLI sub-command base.
 *
 * @since 15xxxx Initial release.
 */
abstract class AbsCliSubCmdBase extends AbsBase
{
    protected $Primary;
    protected $CliOpts;
    protected $CliStream;
    protected $CliColors;
    protected $CliColorize;

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
     * @type OptionResult|Option[] Opts.
     *
     * @since 15xxxx Initial release.
     */
    protected $opts;

    /**
     * @type array Arguments.
     *
     * @since 15xxxx Initial release.
     */
    protected $args;

    /**
     * @type string STDIN (if any).
     *
     * @since 15xxxx Initial release.
     */
    protected $stdin;

    /**
     * Initialize/config.
     *
     * @since 15xxxx Initial release.
     */
    abstract protected function initConfig();

    /**
     * Option specs.
     *
     * @since 15xxxx Initial release.
     *
     * @return array An array of opt. specs.
     */
    abstract protected function optSpecs();

    /**
     * Help output/display.
     *
     * @since 15xxxx Initial release.
     */
    abstract protected function showHelpExit();

    /**
     * Command runner.
     *
     * @since 15xxxx Initial release.
     */
    abstract protected function runOutputExit();

    /**
     * Constructor.
     *
     * @since 15xxxx Initial release.
     */
    final public function __construct(
        AbsCliCmdBase $Primary,
        CliOpts $CliOpts,
        CliStream $CliStream,
        CliColors $CliColors,
        CliColorize $CliColorize
    ) {
        parent::__construct();

        $this->Primary     = $Primary;
        $this->CliOpts     = $CliOpts;
        $this->CliStream   = $CliStream;
        $this->CliColors   = $CliColors;
        $this->CliColorize = $CliColorize;

        # Inherit these from primary.

        $this->version     = $this->Primary->version;
        $this->config      = $this->Primary->config;
        $this->command     = $this->Primary->command;
        $this->sub_command = $this->Primary->sub_command;

        # Initialize any additional config. values.

        $this->initConfig(); // For extenders.

        # Parse options, arguments, STDIN.

        $optSpecs         = $this->optSpecs();
        $optSpecs['help'] = ['desc' => 'Display help file.'];

        $this->CliOpts->add($optSpecs);
        $this->opts = $this->CliOpts->parse();

        $this->args = $this->opts->getArguments();
        array_shift($this->args); // Remove binary.

        $this->stdin = $this->CliStream->in(0, $this::NON_BLOCKING);

        # Maybe show help; else run the sub-command.

        if ($this->opts->help) {
            $this->showHelpExit(); // Display help file.
            exit(0); // Default status for help file.
        }
        $this->runOutputExit(); // Run, output, and exit.
        exit(1); // Command should exit(0) to prevent this.
    }
}
