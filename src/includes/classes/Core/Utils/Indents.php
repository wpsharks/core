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
     * @param mixed       $value Any input value.
     * @param bool|string $trim  Trim to find first line?
     *                           If `true`, regular trimming applies.
     *                           If `'html-trim'`, HTML trimming applies.
     *
     * @return string|array|object Stripped value(s).
     */
    public function stripLeading($value, $trim = true)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->stripLeading($_value, $trim);
            } // unset($_key, $_value);
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        }
        if ($trim === true) { // Default (trim).

            $_string            = $string; // Temporary copy.
            $string             = ''; // Reinitialize; rebuilding below.
            $_line_based_pieces = preg_split('/(['."\r\n".']+)/u', $_string, -1, PREG_SPLIT_DELIM_CAPTURE);

            foreach ($_line_based_pieces as $_index => $_piece) {
                if (!preg_match('/^\s*$/u', $_piece)) {
                    $string = implode(array_slice($_line_based_pieces, $_index));
                    break; // Found the first line that's not just whitespace.
                }
            } // unset($_string, $_line_based_pieces, $_piece); // Housekeeping.
            $string = $this->c::mbRTrim($string); // Now ditch any trailing whitespace.
            //
        } elseif ($trim === 'html-trim') { // HTML trimming in this case.

            $_string            = $string; // Temporary copy.
            $string             = ''; // Reinitialize; rebuilding below.
            $_line_based_pieces = preg_split('/(['."\r\n".']+)/u', $_string, -1, PREG_SPLIT_DELIM_CAPTURE);

            if (($html_whitespace = &$this->cacheKey(__FUNCTION__.'_html_whitespace')) === null) {
                $html_whitespace = implode('|', $this::HTML_WHITESPACE);
            }
            foreach ($_line_based_pieces as $_index => $_piece) {
                if (!preg_match('/^(?:'.$html_whitespace.')*$/u', $_piece)) {
                    $string = implode(array_slice($_line_based_pieces, $_index));
                    break; // Found the first line that's not just whitespace.
                }
            } // unset($_string, $_line_based_pieces, $_piece); // Housekeeping.
            $string = $this->c::htmlRTrim($string); // Now ditch any trailing whitespace.
        }
        if (!$string || !preg_match('/^(?<indentation>['."\t".' ]+)/u', $string, $_m)) {
            return $string; // Empty, or no indentation. Nothing to do.
        }
        return $string = preg_replace('/^'.$_m['indentation'].'/um', '', $string);
    }
}
