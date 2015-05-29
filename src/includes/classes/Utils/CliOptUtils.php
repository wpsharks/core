<?php
namespace WebSharks\Core\Classes\Utils;

/**
 * CLI option utilities.
 *
 * @since 150424 Initial release.
 */
class CliOptUtils extends AbsBase
{
    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        parent::__construct();
    }

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
    public function cliOptsGet($short, array $long = array())
    {
        $short = (string) $short;
        if (!is_array($opts = getopt($short, $long))) {
            $opts = array();
        }
        return (object) $opts;
    }
}
