<?php
namespace WebSharks\Core\Classes;

/**
 * CLI stream utilities.
 *
 * @since 150424 Initial release.
 */
class CliStream extends AbsBase
{
    protected $CliColorize;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(
        CliColorize $CliColorize
    ) {
        parent::__construct();

        $this->CliColorize = $CliColorize;
    }

    /**
     * Read STDIN line.
     *
     * @since 15xxxx Initial release.
     */
    public function in()
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
    public function out($string)
    {
        if (!($string = (string) $string)) {
            return; // Nothing to do.
        }
        if (strpos($string, "\033".'[0m') === false) {
            $string = $this->CliColorize($string);
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
    public function err($string)
    {
        if (!($string = (string) $string)) {
            return; // Nothing to do.
        }
        if (strpos($string, "\033".'[0m') === false) {
            $string = $this->CliColorize($string, 'red_bold');
        }
        fwrite(STDERR, $string."\n");
    }
}
