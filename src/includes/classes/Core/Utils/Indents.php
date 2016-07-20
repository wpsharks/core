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
#
use Pandoc\Pandoc;

/**
 * Indent utilities.
 *
 * @since 160720 Indent utils.
 */
class Indents extends Classes\Core\Base\Core
{
    /**
     * Strip leading indents.
     *
     * @since 160720 Indent utils.
     *
     * @param mixed  $value            Any input value.
     * @param int    $tab_size         Tab size (in spaces).
     * @param string $___tab_in_spaces For internal use only.
     *
     * @return string|array|object Stripped value(s).
     */
    public function stripLeading($value, int $tab_size = 4, string $___tab_in_spaces = null)
    {
        if (!isset($___tab_in_spaces)) {
            $___tab_in_spaces = str_repeat(' ', $tab_size);
        }
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->stripLeading($_value, $tab_size, $___tab_in_spaces);
            } // unset($_key, $_value);
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        } elseif (!($string = $this->c::mbTrim($string, "\r\n"))) {
            return $string; // Nothing but vertical whitespace.
        } elseif (mb_strpos($string, "\t") !== 0 && mb_strpos($string, $___tab_in_spaces) !== 0) {
            return $string; // Nothing to do; no leading indentation.
        }
        $Tokenizer     = $this->c::tokenize($string, ['shortcodes', 'pre', 'code', 'samp', 'md-fences']);
        $string        = &$Tokenizer->getString(); // By reference.
        $string        = preg_replace('/^(?: {'.$tab_size.'}|'."\t".')/um', '', $string);
        return $string = $Tokenizer->restoreGetString();
    }
}
