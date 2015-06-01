<?php
namespace WebSharks\Core\Classes;

use WebSharks\Core\Traits;

/**
 * CLI colorizer.
 *
 * @since 150424 Initial release.
 */
class CliColorize extends AbsBase
{
    use Traits\UrlDefinitions;

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
    public function __invoke($string, $fg_color = 'default', $bg_color = 'default', array $args = [])
    {
        $string   = (string) $string;
        $fg_color = (string) $fg_color;
        $bg_color = (string) $bg_color;

        $default_args = [
            'bold_color'   => 'default_bright',
            'italic_color' => 'default_dim',
            'code_color'   => 'cyan_bright',
            'link_color'   => 'blue_underline',
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        $bold_color   = (string) $args['bold_color'];
        $italic_color = (string) $args['italic_color'];
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
        colorize_bolds: // Target point for bold colorization.

        if ($bold_color && isset($this->CliColors->fg->{$bold_color})) {
            if ($bold_color !== $fg_color && !$bg_color) {
                $colorized_string = preg_replace_callback(
                    '/(?<=^|\s)(\*{2})(?P<bold>[^*]+?)\\1(?=\s|$)/m',
                    function ($m) use ($fg_color, $bold_color) {
                        return "\033".'['.$this->CliColors->fg->{$bold_color}.'m'.$m['bold']."\033".'[0m'.
                                ($fg_color && isset($this->CliColors->fg->{$fg_color}) ? "\033".'['.$this->CliColors->fg->{$fg_color}.'m' : '');
                                // ↑ Restores original color; if there is a foreground color.
                    },
                    $colorized_string // e.g., `**This is a bold sentence.**`
                );
            }
        }
        colorize_italics: // Target point for italic colorization.

        if ($italic_color && isset($this->CliColors->fg->{$italic_color})) {
            if ($italic_color !== $fg_color && !$bg_color) {
                $colorized_string = preg_replace_callback(
                    '/(?<=^|\s)(_)(?P<italic>[^_]+?)\\1(?=\s|$)/m',
                    function ($m) use ($fg_color, $italic_color) {
                        return "\033".'['.$this->CliColors->fg->{$italic_color}.'m'.$m['italic']."\033".'[0m'.
                                ($fg_color && isset($this->CliColors->fg->{$fg_color}) ? "\033".'['.$this->CliColors->fg->{$fg_color}.'m' : '');
                                // ↑ Restores original color; if there is a foreground color.
                    },
                    $colorized_string // e.g., `_This is an italic sentence._`
                );
            }
        }
        colorize_codes: // Target point for code colorization.

        if ($code_color && isset($this->CliColors->fg->{$code_color})) {
            if ($code_color !== $fg_color && !$bg_color) {
                $colorized_string = preg_replace_callback(
                    '/(`+)(?P<code>[^`]+?)\\1/',
                    function ($m) use ($fg_color, $code_color) {
                        return "\033".'['.$this->CliColors->fg->{$code_color}.'m'.$m['code']."\033".'[0m'.
                                ($fg_color && isset($this->CliColors->fg->{$fg_color}) ? "\033".'['.$this->CliColors->fg->{$fg_color}.'m' : '');
                                // ↑ Restores original color; if there is a foreground color.
                    },
                    $colorized_string // e.g., `$this = $colorized_code;`
                );
            }
        }
        colorize_links: // Target point for link colorization.

        if ($link_color && isset($this->CliColors->fg->{$link_color})) {
            if ($link_color !== $fg_color && !$bg_color) {
                $colorized_string = preg_replace_callback(
                    '/(?<o>\<)(?P<link>'.substr($this->DEF_URL_REGEX_VALID, 2, -2).')(?<c>\>)/',
                    function ($m) use ($fg_color, $link_color) {
                        return $m['o']."\033".'['.$this->CliColors->fg->{$link_color}.'m'.$m['link']."\033".'[0m'.
                                ($fg_color && isset($this->CliColors->fg->{$fg_color}) ? "\033".'['.$this->CliColors->fg->{$fg_color}.'m' : '').
                                $m['c']; // ↑ Restores original color; if there is a foreground color.
                    },
                    $colorized_string // e.g., `<http://colorized.link/path/?query>`
                );
            }
        }
        finale: // Target for for grand finale.

        return $colorized_string;
    }
}
