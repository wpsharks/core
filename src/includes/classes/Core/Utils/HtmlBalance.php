<?php
/**
 * HTML balance utilities.
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
 * HTML balance utilities.
 *
 * @since 150424 Initial release.
 */
class HtmlBalance extends Classes\Core\Base\Core
{
    /**
     * Balance HTML markup/tags.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value Any input value.
     *
     * @throws Exception If missing DOM extension.
     *
     * @return string|array|object Balanced value (i.e., HTML markup).
     *
     * @internal This works with HTML fragments only. No on a full document.
     * i.e., If the input contains `</html>` or `</body>` it is left as-is.
     */
    public function tags($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->tags($_value);
            } // unset($_key, $_value);
            return $value;
        }
        if (!($string = (string) $value)) {
            return $string; // Nothing to do.
        } elseif (mb_stripos($string, '</html>') !== false) {
            return $string; // Not a fragment; leave as-is.
        } elseif (mb_stripos($string, '</body>') !== false) {
            return $string; // Not a fragment; leave as-is.
        }
        $html = // UTF-8 encoding.
            '<html>'.
            '   <head>'.
            '       <meta charset="utf-8">'.
            '       <meta http-equiv="content-type" content="text/html; charset=utf-8">'.
            '   </head>'.
            '   <body>'.$string.'</body>'.
            '</html>';
        $Dom = new \DOMDocument();
        $Dom->loadHTML($html);

        $string = $Dom->saveHTML($Dom->getElementsByTagName('body')->item(0));
        $string = preg_replace(['/\<body\>/ui', '/\<\/body\>/ui'], '', $string);
        $string = $this->c::htmlTrim($string);

        return $string;
    }
}
