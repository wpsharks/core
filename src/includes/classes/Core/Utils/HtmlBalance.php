<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * HTML normalizing utilities.
 *
 * @since 150424 Initial release.
 */
class HtmlBalance extends Classes\Core
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
     * @note This works with HTML fragments only. No on a full document.
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
            return $string;
        }
        if (mb_stripos($string, '</html>') !== false) {
            return $string;
        }
        if (mb_stripos($string, '</body>') !== false) {
            return $string;
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
