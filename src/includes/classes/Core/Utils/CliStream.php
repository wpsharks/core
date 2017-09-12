<?php
/**
 * CLI stream utilities.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * CLI stream utilities.
 *
 * @since 150424 Initial release.
 */
class CliStream extends Classes\Core\Base\Core implements Interfaces\UrlConstants, Interfaces\CliConstants
{
    /**
     * Class constructor.
     *
     * @since 150424 Initial release.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        if (!$this->c::isCli()) {
            throw $this->c::issue('Requires CLI mode.');
        }
    }

    /**
     * Read STDIN.
     *
     * @since 150424 Initial release.
     *
     * @param int  $max_lines Defaults to `0` (no limit).
     * @param bool $blocking  Blocking (`true`) or non-blocking?
     *
     * @return string Returns standard input string of X lines.
     */
    public function in(int $max_lines = 0, bool $blocking = true): string
    {
        $lines = 0; // Initialize lines read below.
        $stdin = ''; // Initialize input string.

        stream_set_blocking(STDIN, $blocking);

        while (($_line = fgets(STDIN)) !== false) {
            $stdin .= $_line; // Collect line.
            ++$lines; // Increment line counter.

            if ($max_lines && $lines >= $max_lines) {
                break; // Got what we wanted :-)
            }
        }
        return $this->c::mbTrim($stdin);
    }

    /**
     * Output to STDOUT.
     *
     * @since 150424 Initial release.
     *
     * @param string $string   Output string.
     * @param bool   $colorize Colorize output?
     */
    public function out(string $string, bool $colorize = true)
    {
        if (!$string) {
            return; // Nothing to do.
        }
        if ($colorize) {
            $string = $this->colorize($string);
        }
        stream_set_blocking(STDOUT, true);
        fwrite(STDOUT, $string."\n");
    }

    /**
     * Output HR to STDOUT.
     *
     * @since 150424 Initial release.
     *
     * @param bool $colorize Colorize output?
     */
    public function outHr(bool $colorize = true)
    {
        $this->out($this::CLI_HR, $colorize);
    }

    /**
     * Output to STDERR.
     *
     * @since 150424 Initial release.
     *
     * @param string $string   Output string.
     * @param bool   $colorize Colorize output?
     */
    public function err(string $string, bool $colorize = true)
    {
        if (!$string) {
            return; // Nothing to do.
        }
        if ($colorize) {
            $string = $this->colorize($string, 'red_strong');
        }
        stream_set_blocking(STDERR, true);
        fwrite(STDERR, $string."\n");
    }

    /**
     * Output HR to STDERR.
     *
     * @since 150424 Initial release.
     *
     * @param bool $colorize Colorize output?
     */
    public function errHr(bool $colorize = true)
    {
        $this->out($this::CLI_HR, $colorize);
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
    protected function colorize(
        string $string,
        string $fg_color = 'default',
        string $bg_color = 'default',
        array $args = []
    ): string {
        $default_args = [
            'hr_color'     => 'black',
            'em_color'     => 'default_em',
            'strong_color' => 'default_strong',
            'code_color'   => 'green_strong',
            'link_color'   => 'blue_underline',
        ];
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);

        $hr_color     = (string) $args['hr_color'];
        $em_color     = (string) $args['em_color'];
        $strong_color = (string) $args['strong_color'];
        $code_color   = (string) $args['code_color'];
        $link_color   = (string) $args['link_color'];

        colorize_string: // Target point for colorization.

        $colorized_string = ''; // Initialize string.

        if ($fg_color && isset($this::CLI_FG_COLORS[$fg_color])) {
            $colorized_string .= "\033".'['.$this::CLI_FG_COLORS[$fg_color].'m';
        }
        if ($bg_color && isset($this::CLI_BG_COLORS[$bg_color])) {
            $colorized_string .= "\033".'['.$this::CLI_BG_COLORS[$bg_color].'m';
        }
        $colorized_string .= $string; // The string we are coloring now.

        if ($fg_color || $bg_color) { // Either of the above apply?
            $colorized_string .= "\033".'[0m'; // Reset color.
        }
        colorize_hrs: // Target point for HR colorization.

        if ($hr_color && isset($this::CLI_FG_COLORS[$hr_color])) {
            $colorized_string = preg_replace_callback(
                '/^(?<o>(?:'."\033".'\[[0-9;]+m)+)?(?<hr>\-{3,})(?<c>'."\033".'\[0m)?$/um',
                function ($m) use ($fg_color, $hr_color) {
                    return ($m['o'] ?? '')."\033".'['.$this::CLI_FG_COLORS[$hr_color].'m'.$this::CLI_HR."\033".'[0m'.
                            ($fg_color && isset($this::CLI_FG_COLORS[$fg_color]) ? "\033".'['.$this::CLI_FG_COLORS[$fg_color].'m' : '').
                            ($m['c'] ?? ''); // ↑ Restores original color; if there is a foreground color.
                },
                $colorized_string // e.g., `---` (3 or more).
            );
        }
        colorize_ems: // Target point for emphasis colorization.

        if ($em_color && isset($this::CLI_FG_COLORS[$em_color])) {
            $colorized_string = preg_replace_callback(
                '/_\*(?<em>.+?)\*_/u',
                function ($m) use ($fg_color, $em_color) {
                    return "\033".'['.$this::CLI_FG_COLORS[$em_color].'m'.$m['em']."\033".'[0m'.
                            ($fg_color && isset($this::CLI_FG_COLORS[$fg_color]) ? "\033".'['.$this::CLI_FG_COLORS[$fg_color].'m' : '');
                            // ↑ Restores original color; if there is a foreground color.
                },
                $colorized_string // e.g., _*This is an emphasized sentence.*_
            );
        }
        colorize_strongs: // Target point for strong colorization.

        if ($strong_color && isset($this::CLI_FG_COLORS[$strong_color])) {
            $colorized_string = preg_replace_callback(
                '/(\*{2})(?<strong>.+?)\\1/u',
                function ($m) use ($fg_color, $strong_color) {
                    return "\033".'['.$this::CLI_FG_COLORS[$strong_color].'m'.$m['strong']."\033".'[0m'.
                            ($fg_color && isset($this::CLI_FG_COLORS[$fg_color]) ? "\033".'['.$this::CLI_FG_COLORS[$fg_color].'m' : '');
                            // ↑ Restores original color; if there is a foreground color.
                },
                $colorized_string // e.g., **This is a strong sentence.**
            );
        }
        colorize_codes: // Target point for code colorization.

        if ($code_color && isset($this::CLI_FG_COLORS[$code_color])) {
            $colorized_string = preg_replace_callback(
                '/(`+)(?<code>.+?)\\1/u',
                function ($m) use ($fg_color, $code_color) {
                    return "\033".'['.$this::CLI_FG_COLORS[$code_color].'m'.$m['code']."\033".'[0m'.
                            ($fg_color && isset($this::CLI_FG_COLORS[$fg_color]) ? "\033".'['.$this::CLI_FG_COLORS[$fg_color].'m' : '');
                            // ↑ Restores original color; if there is a foreground color.
                },
                $colorized_string // e.g., `$this = $colorized_code;`
            );
        }
        colorize_links: // Target point for link colorization.

        if ($link_color && isset($this::CLI_FG_COLORS[$link_color])) {
            $colorized_string = preg_replace_callback(
                '/(?<o>\<)(?<link>'.mb_substr($this::URL_REGEX_VALID, 2, -3).')(?<c>\>)/u',
                function ($m) use ($fg_color, $link_color) {
                    return $m['o']."\033".'['.$this::CLI_FG_COLORS[$link_color].'m'.$m['link']."\033".'[0m'.
                            ($fg_color && isset($this::CLI_FG_COLORS[$fg_color]) ? "\033".'['.$this::CLI_FG_COLORS[$fg_color].'m' : '').
                            $m['c']; // ↑ Restores original color; if there is a foreground color.
                },
                $colorized_string // e.g., <http://colorized.link/path/?query>
            );
        }
        finale: // Target for for grand finale.

        return $colorized_string;
    }
}
