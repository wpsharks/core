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
     *
     * @param int    $max_lines Defaults to `0` (no limit).
     * @param string $blocking  Blocking or non-blocking?
     */
    public function in($max_lines = 0, $blocking = self::BLOCKING)
    {
        $lines = 0; // Initialize lines read below.
        $stdin = ''; // Initialize input string.

        stream_set_blocking(STDIN, $blocking === $this::NON_BLOCKING ? 0 : 1);

        while (($_line = fgets(STDIN)) !== false) {
            $stdin .= $_line; // Collect line.
            $lines++; // Increment line counter.

            if ($max_lines && $lines >= $max_lines) {
                break; // Got what we wanted :-)
            }
        }
        return trim($stdin);
    }

    /**
     * Output to STDOUT.
     *
     * @since 150424 Initial release.
     *
     * @param string $string   Output string.
     * @param bool   $colorize Colorize output?
     */
    public function out($string, $colorize = true)
    {
        if (!($string = (string) $string)) {
            return; // Nothing to do.
        }
        $string = $this->CliColorize($string);

        stream_set_blocking(STDOUT, 1);
        fwrite(STDOUT, $string."\n");
    }

    /**
     * Output to STDERR.
     *
     * @since 150424 Initial release.
     *
     * @param string $string   Output string.
     * @param bool   $colorize Colorize output?
     */
    public function err($string, $colorize = true)
    {
        if (!($string = (string) $string)) {
            return; // Nothing to do.
        }
        $string = $this->CliColorize($string, 'red_strong');

        stream_set_blocking(STDERR, 1);
        fwrite(STDERR, $string."\n");
    }
}
