<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;

/**
 * CLI exception utilities.
 *
 * @since 150424 Initial release.
 */
class CliExceptions extends Classes\AbsBase
{
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
     * @param \Exception Exception class instance.
     */
    public function handler(\Exception $Exception)
    {
        $this->Utils->CliStream->err($Exception->getMessage());
        exit(1); // Exit status on exception.
    }
}
