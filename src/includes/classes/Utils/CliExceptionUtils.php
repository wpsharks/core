<?php
namespace WebSharks\Core\Classes\Utils;

/**
 * CLI exception utilities.
 *
 * @since 150424 Initial release.
 */
class CliExceptionUtils extends AbsBase
{
    protected $CliStreamUtils;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(CliStreamUtils $CliStreamUtils)
    {
        parent::__construct();

        $this->CliStreamUtils = $CliStreamUtils;
    }

    /**
     * Setup CLI exception handler.
     *
     * @since 150424 Initial release.
     */
    public function cliExceptionsHandle()
    {
        set_exception_handler(array($this, 'cliExceptionHandle'));
    }

    /**
     * CLI exception handler.
     *
     * @since 150424 Initial release.
     *
     * @param \Exception Exception class instance.
     */
    public function cliExceptionHandle(\Exception $e)
    {
        $this->CliStreamUtils->cliStreamErr($e->getMessage());
    }
}
