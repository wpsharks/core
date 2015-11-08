<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * Watered-down regex.
 *
 * @since 15xxxx Adding watered-down regex.
 */
class WdRegex extends AbsBase
{
    protected $Trim;
    protected $RegexQuote;
    protected $ArrayRemoveEmpties;

    /**
     * Class constructor.
     *
     * @since 15xxxx Adding watered-down regex.
     */
    public function __construct(
        Trim $Trim,
        RegexQuote $RegexQuote,
        ArrayRemoveEmpties $ArrayRemoveEmpties
    ) {
        parent::__construct();

        $this->Trim               = $Trim;
        $this->RegexQuote         = $RegexQuote;
        $this->ArrayRemoveEmpties = $ArrayRemoveEmpties;
    }

    /**
     * Convert watered-down regex to real regex.
     *
     * @since 15xxxx Adding watered-down regex parser.
     *
     * @param string $patterns Watered-down regex patterns; line-delimited.
     * @param string $star_not The behavior of the `*` star. Defaults to excluding `/`.
     * @param bool   $capture  Capture the matches, or use the default `(?:)` syntax?
     *
     * @return string A real regex pattern; ready for {@link preg_match()}.
     */
    public function __invoke(string $patterns, string $star_not = '/', bool $capture = false): string
    {
        $regex = ''; // Initialize.

        $patterns            = preg_split('/['."\r\n".']+/u', $patterns, -1, PREG_SPLIT_NO_EMPTY);
        $patterns            = $this->ArrayRemoveEmpties($this->Trim($patterns));
        $regex_pattern_frags = $this->frag($patterns, $star_not);

        if ($regex_pattern_frags) { // Have an array of regex patterns frags?
            $regex = '/('.($capture ? '' : '?:').implode('|', $regex_pattern_frags).')/ui';
        }
        return $regex;
    }

    /**
     * To true regex fragment.
     *
     * @since 15xxxx Adding watered-down regex.
     *
     * @param mixed  $value    Input value(s) w/ watered-down regex.
     * @param string $star_not The behavior of the `*` star. Defaults to excluding `/`.
     *
     * @return string|array|object Value w/ true regex fragments.
     */
    public function frag($value, string $star_not = '/')
    {
        if (!$star_not) { // Must have this.
            throw new Exception('Missing `star_not` args.');
        }
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->frag($_value, $star_not);
            } // unset($_key, $_value); // Housekeeping.

            return $value;
        }
        $string = (string) $value;

        if (!isset($string[0])) {
            return $string;
        }
        return preg_replace(
            array(
                '/\\\\\^/u',
                '/\\\\\*\\\\\*/u',
                '/\\\\\*/u',
                '/\\\\\$/u',
            ),
            array(
                '^', // Beginning of line.
                '.*?', // Zero or more chars.
                '[^'.$this->RegexQuote($star_not).']*?',
                // Zero or more chars != `$star_not`.
                '$', // End of line.
            ),
            $this->RegexQuote($string)
        );
    }
}
