<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Interfaces;

/**
 * CLI colorizer.
 *
 * @since 150424 Initial release.
 */
class CliColorize extends AbsBase implements Interfaces\UrlConstants
{
    protected $CliColors;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(
        CliColors $CliColors
    ) {
        parent::__construct();

        $this->CliColors = $CliColors;
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
    public function __invoke(string $string, string $fg_color = 'default', string $bg_color = 'default', array $args = []): string
    {
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

        if ($fg_color && isset($this->CliColors->fg->{$fg_color})) {
            $colorized_string .= "\033".'['.$this->CliColors->fg->{$fg_color}.'m';
        }
        if ($bg_color && isset($this->CliColors->bg->{$bg_color})) {
            $colorized_string .= "\033".'['.$this->CliColors->bg->{$bg_color}.'m';
        }
        $colorized_string .= $string; // The string we are coloring now.

        if ($fg_color || $bg_color) {
            $colorized_string .= "\033".'[0m'; // Reset.
        }
        colorize_ems: // Target point for emphasis colorization.

        if ($em_color && isset($this->CliColors->fg->{$em_color})) {
            $colorized_string = preg_replace_callback(
                '/_\*(?P<em>.+?)\*_/u',
                function ($m) use ($fg_color, $em_color) {
                    return "\033".'['.$this->CliColors->fg->{$em_color}.'m'.$m['em']."\033".'[0m'.
                            ($fg_color && isset($this->CliColors->fg->{$fg_color}) ? "\033".'['.$this->CliColors->fg->{$fg_color}.'m' : '');
                            // ↑ Restores original color; if there is a foreground color.
                },
                $colorized_string // e.g., _*This is an emphasized sentence.*_
            );
        }
        colorize_strongs: // Target point for strong colorization.

        if ($strong_color && isset($this->CliColors->fg->{$strong_color})) {
            $colorized_string = preg_replace_callback(
                '/(\*{2})(?P<strong>.+?)\\1/u',
                function ($m) use ($fg_color, $strong_color) {
                    return "\033".'['.$this->CliColors->fg->{$strong_color}.'m'.$m['strong']."\033".'[0m'.
                            ($fg_color && isset($this->CliColors->fg->{$fg_color}) ? "\033".'['.$this->CliColors->fg->{$fg_color}.'m' : '');
                            // ↑ Restores original color; if there is a foreground color.
                },
                $colorized_string // e.g., **This is a strong sentence.**
            );
        }
        colorize_codes: // Target point for code colorization.

        if ($code_color && isset($this->CliColors->fg->{$code_color})) {
            $colorized_string = preg_replace_callback(
                '/(`+)(?P<code>.+?)\\1/u',
                function ($m) use ($fg_color, $code_color) {
                    return "\033".'['.$this->CliColors->fg->{$code_color}.'m'.$m['code']."\033".'[0m'.
                            ($fg_color && isset($this->CliColors->fg->{$fg_color}) ? "\033".'['.$this->CliColors->fg->{$fg_color}.'m' : '');
                            // ↑ Restores original color; if there is a foreground color.
                },
                $colorized_string // e.g., `$this = $colorized_code;`
            );
        }
        colorize_links: // Target point for link colorization.

        if ($link_color && isset($this->CliColors->fg->{$link_color})) {
            $colorized_string = preg_replace_callback(
                '/(?P<o>\<)(?P<link>'.mb_substr($this::URL_REGEX_VALID, 2, -3).')(?P<c>\>)/u',
                function ($m) use ($fg_color, $link_color) {
                    return $m['o']."\033".'['.$this->CliColors->fg->{$link_color}.'m'.$m['link']."\033".'[0m'.
                            ($fg_color && isset($this->CliColors->fg->{$fg_color}) ? "\033".'['.$this->CliColors->fg->{$fg_color}.'m' : '').
                            $m['c']; // ↑ Restores original color; if there is a foreground color.
                },
                $colorized_string // e.g., <http://colorized.link/path/?query>
            );
        }
        finale: // Target for for grand finale.

        return $colorized_string;
    }
}
