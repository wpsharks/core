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

        $this->CliOpts->add($this->optSpecs());
        $this->run(); // Run command; for extenders.
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
    protected function run()
    {
        // For extenders.
    }
}
