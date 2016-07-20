<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Indent utilities.
 *
 * @since 160720 Indent utils.
 */
class Indents extends Classes\Core\Base\Core implements Interfaces\HtmlConstants
{
    /**
     * Strip leading indents.
     *
     * @since 160720 Indent utils.
     *
     * @param mixed  $value            Any input value.
     * @param int    $tab_size         Tab size (in spaces).
     * @param bool   $trim             Trim & find first line?
     * @param string $___tab_in_spaces For internal use only.
     *
     * @return string|array|object Stripped value(s).
     */
    public function stripLeading($value, int $tab_size = 4, bool $trim = true, string $___tab_in_spaces = null)
    {
        if (!isset($___tab_in_spaces)) {
            $___tab_in_spaces = str_repeat(' ', $tab_size);
        }
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->stripLeading($_value, $tab_size, $trim, $___tab_in_spaces);
            } // unset($_key, $_value);
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        if ($trim) { // Trim & find first line?

            $_string            = $string; // Temporary copy.
            $string             = ''; // Reinitialize; rebuilding below.
            $_line_based_pieces = preg_split('/(['."\r\n".']+)/u', $_string, -1, PREG_SPLIT_DELIM_CAPTURE);

            if (($html_whitespace = &$this->cacheKey(__FUNCTION__.'_html_whitespace')) === null) {
                $html_whitespace = implode('|', $this::HTML_WHITESPACE);
            }
            foreach ($_line_based_pieces as $_index => $_piece) {
                if (!preg_match('/^(?:'.$html_whitespace.')*$/u', $_piece)) {
                    $string = implode('', array_slice($_line_based_pieces, $_index));
                    break; // Found the first line that's not just whitespace.
                }
            } // unset($_string, $_line_based_pieces, $_piece); // Housekeeping.

            $string = $this->c::htmlRTrim($string); // Now ditch any trailing whitespace.
        }
        if (!$string || (mb_strpos($string, "\t") !== 0 && mb_strpos($string, $___tab_in_spaces) !== 0)) {
            return $string; // Nothing to do; no leading indentation.
        }
        return $string = preg_replace('/^(?: {'.$tab_size.'}|'."\t".')/um', '', $string);
    }
}
