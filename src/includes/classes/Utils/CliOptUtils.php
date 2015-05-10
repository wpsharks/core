<?php
namespace WebSharks\Core\Traits;

/**
 * CLI option utilities.
 *
 * @since 150424 Initial release.
 */
trait CliOptUtils
{
    /**
     * Get options.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $short Short options.
     * @param array  $long  Long options (optional).
     *
     * @return \stdClass Options.
     */
    protected function cliOptsGet($short, array $long = array())
    {
        $short = (string) $short;
        if (!is_array($opts = getopt($short, $long))) {
            $opts = array();
        }
        return (object) $opts;
    }
}
