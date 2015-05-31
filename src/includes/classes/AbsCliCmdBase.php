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
    protected $Opts;

    /**
     * Constructor.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $short Short options.
     * @param array  $long  Long options.
     */
    public function __construct(
        CliOpts $CliOpts,
        CliStream $CliStream,
        CliColorize $CliColorize,
        AbsCliBase $Cli,
        $short,
        array $long = []
    ) {
        parent::__construct();

        $this->CliOpts     = $CliOpts;
        $this->CliStream   = $CliStream;
        $this->CliColorize = $CliColorize;

        $this->Cli  = $Cli; // Parent/primary.
        $this->Opts = $this->CliOpts($short, $long);
    }
}
