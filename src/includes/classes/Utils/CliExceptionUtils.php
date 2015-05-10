<?php
namespace WebSharks\Core\Traits;

/**
 * CLI exception utilities.
 *
 * @since 150424 Initial release.
 */
trait CliExceptionUtils
{
    abstract protected function cliStreamErr($string);

    /**
     * Setup CLI exception handler.
     *
     * @since 150424 Initial release.
     */
    protected function cliExceptionsHandle()
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
        $this->cliStreamErr($e->getMessage());
    }
}
