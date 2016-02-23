<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Output utilities.
 *
 * @since 150424 Initial release.
 */
class Output extends Classes\Core
{
    /**
     * Disables GZIP compression.
     *
     * @since 150424 Initial release.
     */
    public function gzipOff()
    {
        if (headers_sent()) {
            throw new Exception('Heading already sent!');
        }
        @ini_set('zlib.output_compression', 'off');
        if (c\can_call_func('apache_setenv')) {
            @apache_setenv('no-gzip', '1');
        }
    }

    /**
     * Ends/cleans any open output buffers.
     *
     * @since 150424 Initial release.
     */
    public function buffersEndClean()
    {
        if (headers_sent()) {
            throw new Exception('Heading already sent!');
        }
        while (@ob_end_clean()) {
            // End & clean any open buffers.
        }
    }
}
