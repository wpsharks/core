<?php
/**
 * Throwable utilities.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Throwable utilities.
 *
 * @since 160711 Throwables.
 */
class Throwables extends Classes\Core\Base\Core
{
    /**
     * Setup handler.
     *
     * @since 160711 Throwables.
     */
    public function handle()
    {
        set_exception_handler([$this, 'handler']);
    }

    /**
     * Throwable handler.
     *
     * @since 160711 Throwables.
     *
     * @param \Throwable $Throwable Error/Exception.
     */
    public function handler(\Throwable $Throwable)
    {
        if ($this->c::isCli()) {
            // Set `STDERR` so that it can be used in CLI feedback.
            // If debugging, `STDERR` should include a full stack trace.
            // If it's not an interactive terminal session, try to log the error.
            // The exit status should always be `1` to indicate an error.

            try { // Catch throwables.

                $this->c::noCacheFlags();
                $this->c::sessionWriteClose();
                $this->c::obEndCleanAll();

                if ($this->App->Config->©debug['©enable']) {
                    $this->c::writeStderr($Throwable->__toString());
                } else {
                    $this->c::writeStderr($Throwable->getMessage());
                }
                if (!$this->c::isCliInteractive()) {
                    error_log(str_replace("\0", '', $Throwable->__toString()));
                }
                exit(1); // Exit status code.
                //
            } catch (\Throwable $inner_Throwable) { // Edge case.
                exit(1); // Simply exit in this edge case.
            }
        } elseif (!headers_sent()) { // Send a 500 error response code.
            // If there is a throwable template, use the throwable template.
            // In either case, rethrow; i.e., allow PHP to log as an error.
            // It's also IMPORTANT to rethrow so that execution stops!

            try { // Catch throwables.

                $this->c::noCacheFlags();
                $this->c::sessionWriteClose();
                $this->c::obEndCleanAll();

                $this->c::statusHeader(500);
                $this->c::noCacheHeaders();
                header('content-type: text/html; charset=utf-8');

                echo $this->c::getTemplate('http/html/status/500.php')
                    ->parse(['Throwable' => $Throwable]);
                //
            } catch (\Throwable $inner_Throwable) {
                echo 'Unexpected error. Please try again.'."\n"; // Edge case.
            }
            throw $Throwable; // Rethrow. i.e., allow PHP to log as an error.
            // ↑ NOTE: Assumes throwables will not be handled here when `display_errors=yes`.
            // Therefore, when the above template is displayed, that's all you'll see in most cases.
            // i.e., Under most conditions, the display of this PHP error should not be seen. Only logged.
        } else {
            // Should be avoided. It's always better to buffer output so that an error
            // can be shown instead of what would have been output to a browser otherwise.
            // Our own template handler uses output buffering so this is not an issue with core.
            throw $Throwable; // Rethrow. i.e., log and/or display if debugging.
        }
    }
}
