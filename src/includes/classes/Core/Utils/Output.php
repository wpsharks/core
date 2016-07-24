<?php
/**
 * Output utilities.
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
     */
    public function filePrep()
    {
        $this->gzipOff();
        $this->sessionWriteClose();
        $this->buffersEndClean();
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
}
