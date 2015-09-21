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
    protected $PhpExecTime;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(
        PhpHas $PhpHas,
        PhpExecTime $PhpExecTime
    ) {
        parent::__construct();

        $this->PhpHas      = $PhpHas;
        $this->PhpExecTime = $PhpExecTime;
    }

    /**
     * Prepares for output delivery.
     *
     * @since 150424 Initial release.
     */
    public function prep()
    {
        $this->PhpExecTime->max(0);
        $this->gZipCompressionOff();
        $this->buffersEndClean();
    }

    /**
     * Disables GZIP compression.
     *
     * @since 150424 Initial release.
     */
    public function gZipCompressionOff()
    {
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
        while (@ob_end_clean()) {
            // Clean buffers :-)
        }
    }
}
