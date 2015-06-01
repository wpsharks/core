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
    public $fg = [
        'black'  => '0;30',
        'red'    => '0;31',
        'green'  => '0;32',
        'yellow' => '0;33',
        'blue'   => '0;34',
        'purple' => '0;35',
        'cyan'   => '0;36',
        'white'  => '0;37',

        'black_bright'  => '1;30',
        'red_bright'    => '1;31',
        'green_bright'  => '1;32',
        'yellow_bright' => '1;33',
        'blue_bright'   => '1;34',
        'purple_bright' => '1;35',
        'cyan_bright'   => '1;36',
        'white_bright'  => '1;37',

        'black_dim'  => '2;30',
        'red_dim'    => '2;31',
        'green_dim'  => '2;32',
        'yellow_dim' => '2;33',
        'blue_dim'   => '2;34',
        'purple_dim' => '2;35',
        'cyan_dim'   => '2;36',
        'white_dim'  => '2;37',

        'black_underline'  => '4;30',
        'red_underline'    => '4;31',
        'green_underline'  => '4;32',
        'yellow_underline' => '4;33',
        'blue_underline'   => '4;34',
        'purple_underline' => '4;35',
        'cyan_underline'   => '4;36',
        'white_underline'  => '4;37',

        'black_blink'  => '5;30',
        'red_blink'    => '5;31',
        'green_blink'  => '5;32',
        'yellow_blink' => '5;33',
        'blue_blink'   => '5;34',
        'purple_blink' => '5;35',
        'cyan_blink'   => '5;36',
        'white_blink'  => '5;37',
    ];

    /**
     * Background colors.
     *
     * @since 150424 Initial release.
     *
     * @type \stdClass BG colors.
     */
    public $bg = [
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

        $this->fg = (object) $this->fg;
        $this->bg = (object) $this->bg;
    }
}
