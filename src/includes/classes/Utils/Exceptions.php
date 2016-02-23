<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Exception utilities.
 *
 * @since 150424 Initial release.
 */
class Exceptions extends Classes\Core
{
    /**
     * Setup exception handler.
     *
     * @since 150424 Initial release.
     */
    public function handle() // Sets the handler.
    {
        set_exception_handler(array($this, 'handler'));
    }

    /**
     * Exception handler.
     *
     * @since 150424 Initial release.
     *
     * @param \Throwable Exception class instance.
     */
    public function handler(\Throwable $Exception)
    {
        if (c\is_cli()) {
            // Set `STDERR` so that it can be used in CLI feedback.
            // If debugging, `STDERR` should include a full stack trace.
            // If it's not an interactive terminal session, try to log the error.
            // The exit status should always be `1` to indicate an error.

            try { // Catch any inner exceptions.

                c\ob_end_clean_all();

                if ($this->App->Config->debug) {
                    c\write_stderr($Exception->__toString());
                } else {
                    c\write_stderr($Exception->getMessage());
                }
                if (!c\is_cli_interactive()) {
                    error_log(str_replace("\0", '', $Exception->__toString()));
                }
                exit(1); // Exit status code (generic error code is `1`).
                //
            } catch (\Throwable $InnerException) {
                exit(1); // Simply exit in this edge case.
            }
        } elseif (!headers_sent()) { // Send a 500 error response code.
            // If there is an exception template, use the exception template.
            // In either case, rethrow the exception; i.e., maybe log as fatal error.

            try { // Catch any inner exceptions.

                c\ob_end_clean_all();
                c\status_header(500);

                echo c\get_template('http/html/status/500.php')
                    ->parse(['Exception' => $Exception]);
                //
            } catch (\Throwable $InnerException) {
                echo 'Unexpected error. Please contact us if you need help.'."\n";
            }
            throw $Exception; // Rethrow; i.e., log and/or display error if debugging.
        } else {
            // This case should be avoided; i.e., always buffer output so that an error
            //  can be shown instead of what would have been output to a browser otherwise.
            // Our own template handler uses output buffering, so this is not an issue with core.
            //
            throw $Exception; // Rethrow; i.e., log and/or display error if debugging.
        }
    }
}
