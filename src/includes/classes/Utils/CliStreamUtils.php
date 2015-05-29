<?php
namespace WebSharks\Core\Classes\Utils;

/**
 * CLI stream utilities.
 *
 * @since 150424 Initial release.
 */
class CliStreamUtils extends AbsBase
{
    protected $CliColorUtils;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(CliColorUtils $CliColorUtils)
    {
        parent::__construct();

        $this->CliColorUtils = $CliColorUtils;
    }

    /**
     * Read STDIN line.
     *
     * @since 15xxxx Initial release.
     */
    public function cliStreamIn()
    {
        $stdin = ''; // Initialize.
        while (($_line = fgets(STDIN)) !== false) {
            $stdin .= $_line;
        }
        return $stdin;
    }

    /**
     * Output to STDOUT.
     *
     * @since 150424 Initial release.
     *
     * @param string $string Output string.
     */
    public function cliStreamOut($string)
    {
        if (!($string = (string) $string)) {
            return; // Nothing to do.
        }
        if (strpos($string, "\033".'[0m') === false) {
            $string = $this->CliColorUtils->cliColorize($string);
        }
        fwrite(STDOUT, $string."\n");
    }

    /**
     * Output to STDERR.
     *
     * @since 150424 Initial release.
     *
     * @param string $string Output string.
     */
    public function cliStreamErr($string)
    {
        if (!($string = (string) $string)) {
            return; // Nothing to do.
        }
        if (strpos($string, "\033".'[0m') === false) {
            $string = $this->CliColorUtils->cliColorize($string, 'red_bold');
        }
        fwrite(STDERR, $string."\n");
    }
}
