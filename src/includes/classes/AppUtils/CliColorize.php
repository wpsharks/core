<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * CLI colorizer.
 *
 * @since 150424 Initial release.
 */
class CliColorize extends Classes\AbsBase implements Interfaces\UrlConstants
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
     * Colorizer.
     *
     * @since 150424 Initial release.
     *
     * @param string $string   Input string to colorize.
     * @param string $fg_color Foreground color to use.
     * @param string $bg_color Background color to use.
     * @param array  $args     Any additional argument values.
     *
     * @return string Colorized input string.
     */
    public function __invoke(
        string $string,
        string $fg_color = 'default',
        string $bg_color = 'default',
        array $args = []
    ): string {
        $default_args = [
            'em_color'     => 'default_em',
            'strong_color' => 'default_strong',
            'code_color'   => 'green_strong',
            'link_color'   => 'blue_underline',
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        $em_color     = (string) $args['em_color'];
        $strong_color = (string) $args['strong_color'];
        $code_color   = (string) $args['code_color'];
        $link_color   = (string) $args['link_color'];

        colorize_string: // Target point for colorization.

        $colorized_string = ''; // Initialize string.

        if ($fg_color && isset($this::FG[$fg_color])) {
            $colorized_string .= "\033".'['.$this::FG[$fg_color].'m';
        }
        if ($bg_color && isset($this::BG[$bg_color])) {
            $colorized_string .= "\033".'['.$this::BG[$bg_color].'m';
        }
        $colorized_string .= $string; // The string we are coloring now.

        if ($fg_color || $bg_color) {
            $colorized_string .= "\033".'[0m'; // Reset.
        }
        colorize_ems: // Target point for emphasis colorization.

        if ($em_color && isset($this::FG[$em_color])) {
            $colorized_string = preg_replace_callback(
                '/_\*(?<em>.+?)\*_/u',
                function ($m) use ($fg_color, $em_color) {
                    return "\033".'['.$this::FG[$em_color].'m'.$m['em']."\033".'[0m'.
                            ($fg_color && isset($this::FG[$fg_color]) ? "\033".'['.$this::FG[$fg_color].'m' : '');
                            // ↑ Restores original color; if there is a foreground color.
                },
                $colorized_string // e.g., _*This is an emphasized sentence.*_
            );
        }
        colorize_strongs: // Target point for strong colorization.

        if ($strong_color && isset($this::FG[$strong_color])) {
            $colorized_string = preg_replace_callback(
                '/(\*{2})(?<strong>.+?)\\1/u',
                function ($m) use ($fg_color, $strong_color) {
                    return "\033".'['.$this::FG[$strong_color].'m'.$m['strong']."\033".'[0m'.
                            ($fg_color && isset($this::FG[$fg_color]) ? "\033".'['.$this::FG[$fg_color].'m' : '');
                            // ↑ Restores original color; if there is a foreground color.
                },
                $colorized_string // e.g., **This is a strong sentence.**
            );
        }
        colorize_codes: // Target point for code colorization.

        if ($code_color && isset($this::FG[$code_color])) {
            $colorized_string = preg_replace_callback(
                '/(`+)(?<code>.+?)\\1/u',
                function ($m) use ($fg_color, $code_color) {
                    return "\033".'['.$this::FG[$code_color].'m'.$m['code']."\033".'[0m'.
                            ($fg_color && isset($this::FG[$fg_color]) ? "\033".'['.$this::FG[$fg_color].'m' : '');
                            // ↑ Restores original color; if there is a foreground color.
                },
                $colorized_string // e.g., `$this = $colorized_code;`
            );
        }
        colorize_links: // Target point for link colorization.

        if ($link_color && isset($this::FG[$link_color])) {
            $colorized_string = preg_replace_callback(
                '/(?<o>\<)(?<link>'.mb_substr($this::URL_REGEX_VALID, 2, -3).')(?<c>\>)/u',
                function ($m) use ($fg_color, $link_color) {
                    return $m['o']."\033".'['.$this::FG[$link_color].'m'.$m['link']."\033".'[0m'.
                            ($fg_color && isset($this::FG[$fg_color]) ? "\033".'['.$this::FG[$fg_color].'m' : '').
                            $m['c']; // ↑ Restores original color; if there is a foreground color.
                },
                $colorized_string // e.g., <http://colorized.link/path/?query>
            );
        }
        finale: // Target for for grand finale.

        return $colorized_string;
    }

    /**
     * Background colors.
     *
     * @since 150424 Initial release.
     *
     * @type \stdClass BG colors.
     */
    const BG = [
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
     * @since 150424 Initial release.
     *
     * @type \stdClass FG colors.
     */
    const FG = [
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
}
