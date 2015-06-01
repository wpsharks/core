<?php
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

        $this->initConfig(); // For extenders.

        $optSpecs         = $this->optSpecs();
        $optSpecs['help'] = ['desc' => 'Display help file.'];

        $this->CliOpts->add($optSpecs);
        $this->opts = $this->CliOpts->parse();

        $this->args = $this->opts->getArguments();
        array_shift($this->args); // Remove binary.

        $this->stdin = $this->CliStream->in(0, $this::NON_BLOCKING);

        if ($this->opts->help) {
            $this->showHelp(); // Display help file.
            exit(0); // All done here.
        }
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
     * Help output/display.
     *
     * @since 15xxxx Initial release.
     */
    protected function showHelp()
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
