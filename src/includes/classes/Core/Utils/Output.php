<?php
/**
 * Output utilities.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Output utilities.
 *
 * @since 150424 Initial release.
 */
class Output extends Classes\Core\Base\Core
{
    /**
     * Prepares for file output.
     *
     * @since 160622 Adding file output prep.
     * @since 170824.30708 `filePrep()` is now `prepFile()`.
     */
    public function prepFile()
    {
        $this->gzipOff();
        $this->buffersEndClean();
        $this->sessionWriteClose();
    }

    /**
     * Closes a request early.
     *
     * @since 170824.30708 Early close of request.
     */
    public function closeRequestEarly()
    {
        ignore_user_abort(true);
        $this->sessionWriteClose();

        if ($this->c::canCallFunc('fastcgi_finish_request')) {
            fastcgi_finish_request();
        } else {
            $this->buffersEndFlush();
            header('connection: close');
        }
    }

    /**
     * Disables GZIP compression.
     *
     * @since 150424 Initial release.
     */
    public function gzipOff()
    {
        if (headers_sent()) {
            throw $this->c::issue('Heading already sent!');
        }
        @ini_set('zlib.output_compression', 'off');

        if ($this->c::canCallFunc('apache_setenv')) {
            @apache_setenv('no-gzip', '1');
        }
    }

    /**
     * Writes/closes any open session data.
     *
     * @since 160622 Adding session write/close.
     */
    public function sessionWriteClose()
    {
        if (headers_sent()) {
            throw $this->c::issue('Heading already sent!');
        }
        @session_write_close(); // End and write session data.
    }

    /**
     * Ends/cleans any open output buffers.
     *
     * @since 150424 Initial release.
     */
    public function buffersEndClean()
    {
        if (headers_sent()) {
            throw $this->c::issue('Heading already sent!');
        }
        while (@ob_end_clean()) {
            // End & clean any open buffers.
        }
    }

    /**
     * Ends/flushes any open output buffers.
     *
     * @since 170824.30708 Buffer end/flush.
     */
    public function buffersEndFlush()
    {
        if (headers_sent()) {
            throw $this->c::issue('Heading already sent!');
        }
        while (@ob_end_flush()) {
            // End & flush any open buffers.
        }
    }
}
