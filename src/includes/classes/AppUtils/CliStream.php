<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * CLI stream utilities.
 *
 * @since 150424 Initial release.
 */
class CliStream extends Classes\AbsBase
{
    /**
     * Class constructor.
     *
     * @since 150424 Initial release.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        if (!$this->Utils->Cli->is()) {
            throw new Exception('Requires CLI mode.');
        }
    }

    /**
     * Read STDIN.
     *
     * @since 15xxxx Initial release.
     *
     * @param int  $max_lines Defaults to `0` (no limit).
     * @param bool $blocking  Blocking (`true`) or non-blocking?
     *
     * @return string Returns standard input string of X lines.
     */
    public function in(int $max_lines = 0, bool $blocking = true): string
    {
        $lines = 0; // Initialize lines read below.
        $stdin = ''; // Initialize input string.

        stream_set_blocking(STDIN, (int) $blocking);

        while (($_line = fgets(STDIN)) !== false) {
            $stdin .= $_line; // Collect line.
            ++$lines; // Increment line counter.

            if ($max_lines && $lines >= $max_lines) {
                break; // Got what we wanted :-)
            }
        }
        return $this->Utils->Trim($stdin);
    }

    /**
     * Output to STDOUT.
     *
     * @since 150424 Initial release.
     *
     * @param string $string   Output string.
     * @param bool   $colorize Colorize output?
     */
    public function out(string $string, bool $colorize = true)
    {
        if (!$string) {
            return; // Nothing to do.
        }
        $string = $this->Utils->CliColorize($string);

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
    public function err(string $string, bool $colorize = true)
    {
        if (!$string) {
            return; // Nothing to do.
        }
        $string = $this->Utils->CliColorize($string, 'red_strong');

        stream_set_blocking(STDERR, 1);
        fwrite(STDERR, $string."\n");
    }
}
