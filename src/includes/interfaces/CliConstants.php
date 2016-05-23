<?php
declare (strict_types = 1);
namespace WebSharks\Core\Interfaces;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function get_defined_vars as vars;

/**
 * CLI constants.
 *
 * @since 151213 Colors.
 */
interface CliConstants
{
    /**
     * Background colors.
     *
     * @since 151213 Colors.
     *
     * @type array
     */
    const CLI_BG_COLORS = [
        'black'      => '40',
        'red'        => '41',
        'green'      => '42',
        'yellow'     => '43',
        'blue'       => '44',
        'magenta'    => '45',
        'cyan'       => '46',
        'light_gray' => '47',
        'default'    => '49',
    ];

    /**
     * Foreground colors.
     *
     * @since 151213 Colors.
     *
     * @type array
     */
    const CLI_FG_COLORS = [
        'black'             => '0;30',
        'red'               => '0;31',
        'green'             => '0;32',
        'yellow'            => '0;33',
        'blue'              => '0;34',
        'purple'            => '0;35',
        'cyan'              => '0;36',
        'white'             => '0;37',
        'default'           => '0;39',
        'black_em'          => '3;30',
        'red_em'            => '3;31',
        'green_em'          => '3;32',
        'yellow_em'         => '3;33',
        'blue_em'           => '3;34',
        'purple_em'         => '3;35',
        'cyan_em'           => '3;36',
        'white_em'          => '3;37',
        'default_em'        => '3;39',
        'black_strong'      => '1;30',
        'red_strong'        => '1;31',
        'green_strong'      => '1;32',
        'yellow_strong'     => '1;33',
        'blue_strong'       => '1;34',
        'purple_strong'     => '1;35',
        'cyan_strong'       => '1;36',
        'white_strong'      => '1;37',
        'default_strong'    => '1;39',
        'black_underline'   => '4;30',
        'red_underline'     => '4;31',
        'green_underline'   => '4;32',
        'yellow_underline'  => '4;33',
        'blue_underline'    => '4;34',
        'purple_underline'  => '4;35',
        'cyan_underline'    => '4;36',
        'white_underline'   => '4;37',
        'default_underline' => '4;39',
    ];

    /**
     * Horizontal rule.
     *
     * @since 160110 HR.
     *
     * @type string
     */
    const CLI_HR = '----------------------------------------------------------------------';
}
