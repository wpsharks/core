<?php
namespace WebSharks\Core\Classes;

/**
 * CLI command base.
 *
 * @since 15xxxx Initial release.
 */
abstract class AbsCliCmdBase extends AbsBase
{
    protected $CliStream;
    protected $CliColors;
    protected $CliColorize;
    protected $CliOpts;
    protected $Cli;

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
     * Constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(
        CliStream $CliStream,
        CliColorize $CliColors,
        CliColorize $CliColorize,
        CliOpts $CliOpts,
        AbsCliBase $Cli
    ) {
        parent::__construct();

        $this->CliStream   = $CliStream;
        $this->CliColors   = $CliColors;
        $this->CliColorize = $CliColorize;
        $this->CliOpts     = $CliOpts;
        $this->Cli         = $Cli;

        $this->initConfig(); // For extenders.

        $this->CliOpts->add($this->optSpecs());
        $this->opts = $this->CliOpts->parse();

        $this->args = $this->opts->getArguments();
        array_shift($this->args); // Remove binary.

        $this->stdin = $this->CliStream->in(0, $this::NON_BLOCKING);

        $this->runCommand(); // For extenders.
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
     * Option specs.
     *
     * @since 15xxxx Initial release.
     *
     * @return array An array of opt. specs.
     */
    protected function optSpecs()
    {
        return []; // For extenders.
    }

    /**
     * Command runner.
     *
     * @since 15xxxx Initial release.
     */
    protected function runCommand()
    {
        // For extenders.
    }
}
