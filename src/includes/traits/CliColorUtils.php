<?php
namespace WebSharks\Core\Traits;

/**
 * CLI color utilities.
 *
 * @since 150424 Initial release.
 */
trait CliColorUtils
{
    /**
     * Foreground colors.
     *
     * @since 150424 Initial release.
     *
     * @type array FG colors.
     */
    protected $cli_colors_fg = [
        'black'  => '0;30',
        'red'    => '0;31',
        'green'  => '0;32',
        'yellow' => '0;33',
        'blue'   => '0;34',
        'purple' => '0;35',
        'cyan'   => '0;36',
        'white'  => '0;37',

        'black_bold'  => '1;30',
        'red_bold'    => '1;31',
        'green_bold'  => '1;32',
        'yellow_bold' => '1;33',
        'blue_bold'   => '1;34',
        'purple_bold' => '1;35',
        'cyan_bold'   => '1;36',
        'white_bold'  => '1;37',

        'black_underline'  => '4;30',
        'red_underline'    => '4;31',
        'green_underline'  => '4;32',
        'yellow_underline' => '4;33',
        'blue_underline'   => '4;34',
        'purple_underline' => '4;35',
        'cyan_underline'   => '4;36',
        'white_underline'  => '4;37',
    ];

    /**
     * Background colors.
     *
     * @since 150424 Initial release.
     *
     * @type array BG colors.
     */
    protected $cli_colors_bg = [
        'black'      => '40',
        'red'        => '41',
        'green'      => '42',
        'yellow'     => '43',
        'blue'       => '44',
        'magenta'    => '45',
        'cyan'       => '46',
        'light_gray' => '47',
    ];

    /**
     * Colorizer.
     *
     * @since 150424 Initial release.
     *
     * @param string $string   Input string to colorize.
     * @param string $fg_color Foreground color to use.
     * @param string $bg_color Background color to use.
     *
     * @return string Colorized input string.
     */
    protected function cliColorize($string, $fg_color = '', $bg_color = '')
    {
        $string   = (string) $string;
        $fg_color = (string) $fg_color;
        $bg_color = (string) $bg_color;

        $colorized_string = ''; // Initialize.

        if ($fg_color && isset($this->cli_colors_fg[$fg_color])) {
            $colorized_string .= "\033".'['.$this->cli_colors_fg[$fg_color].'m';
        }
        if ($bg_color && isset($this->cli_colors_bg[$bg_color])) {
            $colorized_string .= "\033".'['.$this->cli_colors_bg[$bg_color].'m';
        }
        $colorized_string .= $string; // String itself.

        if ($fg_color || $bg_color) {
            $colorized_string .= "\033".'[0m';
        }
        return $colorized_string;
    }
}
