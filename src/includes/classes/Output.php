<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * Output utilities.
 *
 * @since 150424 Initial release.
 */
class Output extends AbsBase
{
    protected $PhpHas;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(
        PhpHas $PhpHas
    ) {
        parent::__construct();

        $this->PhpHas = $PhpHas;
    }
    /**
     * Disables GZIP compression.
     *
     * @since 150424 Initial release.
     */
    public function gZipCompressionOff()
    {
        if (headers_sent()) {
            throw new Exception('Heading already sent!');
        }
        @ini_set('zlib.output_compression', 0);
        if ($this->PhpHas->callableFunction('apache_setenv')) {
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
