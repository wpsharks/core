<?php
namespace WebSharks\Core\Classes;

/**
 * CLI colors.
 *
 * @since 150424 Initial release.
 */
class CliColors extends AbsBase
{
    /**
     * Foreground colors.
     *
     * @since 150424 Initial release.
     *
     * @type \stdClass FG colors.
     */
    public $Fg = [
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
     * @type \stdClass BG colors.
     */
    public $Bg = [
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
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        parent::__construct();

        $this->Fg = (object) $this->Fg;
        $this->Bg = (object) $this->Bg;
    }
}
