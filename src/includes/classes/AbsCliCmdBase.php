<?php
namespace WebSharks\Core\Classes;

/**
 * CLI command base.
 *
 * @since 15xxxx Initial release.
 */
abstract class AbsCliCmdBase extends AbsBase
{
    protected $CliOpts;
    protected $CliStream;
    protected $CliColors;
    protected $CliColorize;

    /**
     * @type AbsCliBase
     *
     * @since 15xxxx Initial release.
     */
    protected $Cli;

    /**
     * @type \stdClass Opts.
     *
     * @since 15xxxx Initial release.
     */
    protected $opts;

    /**
     * @type string Short opts.
     *
     * @since 15xxxx Initial release.
     */
    protected $short_opts = '';

    /**
     * @type array Long opts.
     *
     * @since 15xxxx Initial release.
     */
    protected $long_opts = [];

    /**
     * Constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(
        CliOpts $CliOpts,
        CliStream $CliStream,
        CliColorize $CliColors,
        CliColorize $CliColorize,
        AbsCliBase $Cli
    ) {
        parent::__construct();

        $this->CliOpts     = $CliOpts;
        $this->CliStream   = $CliStream;
        $this->CliColors   = $CliColors;
        $this->CliColorize = $CliColorize;
        $this->Cli         = $Cli; // Parent/primary.
        $this->opts        = $this->CliOpts($this->short_opts, $this->long_opts);

        $this->run(); // Run the command.
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
