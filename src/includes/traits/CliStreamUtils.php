<?php
namespace WebSharks\Core\Traits;

/**
 * CLI stream utilities.
 *
 * @since 150424 Initial release.
 */
trait CliStreamUtils
{
    abstract protected function cliColorize($string, $fg_color = '', $bg_color = '', array $args = []);

    /**
     * Read STDIN line.
     *
     * @since 15xxxx Initial release.
     */
    protected function cliStreamIn()
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
    protected function cliStreamOut($string)
    {
        if (!($string = (string) $string)) {
            return; // Nothing to do.
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
    protected function cliStreamErr($string)
    {
        if (!($string = (string) $string)) {
            return; // Nothing to do.
        }
        if (strpos($string, "\033".'[0m') === false) {
            $string = $this->cliColorize($string, 'red_bold');
        }
        fwrite(STDERR, $string."\n");
    }
}
