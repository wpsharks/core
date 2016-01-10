<?php
namespace WebSharks\Core\Classes;

/**
 * CLI exception utilities.
 *
 * @since 150424 Initial release.
 */
class CliExceptions extends AbsBase
{
    protected $CliStream;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(
        CliStream $CliStream
    ) {
        parent::__construct();

        $this->CliStream = $CliStream;
    }

    /**
     * Setup CLI exception handler.
     *
     * @since 150424 Initial release.
     */
    public function handle()
    {
        set_exception_handler(array($this, 'handler'));
    }

    /**
     * CLI exception handler.
     *
     * @since 150424 Initial release.
     *
     * @param \Exception|\Throwable Exception class instance.
     */
    public function handler($e)
    {
        $this->CliStream->err($e->getMessage());
        exit(1); // Exit status on exception.
    }
}
