<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;

/**
 * HTML normalizing utilities.
 *
 * @since 150424 Initial release.
 */
class HtmlBalance extends Classes\AbsBase
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
            }
            unset($_key, $_value); // Housekeeping.

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
            '       <meta charset="UTF-8">'.
            '       <meta http-equiv="content-type" content="text/html; charset=utf-8">'.
            '   </head>'.
            '   <body>'.$string.'</body>'.
            '</html>';
        if (!$this->Utils->PhpHas->extension('dom')) {
            throw new Exception('DOM extension missing.');
        }
        $Dom = new \DOMDocument();
        $Dom->loadHTML($html);

        $string = $Dom->saveHTML($Dom->getElementsByTagName('body')->item(0));
        $string = preg_replace(['/\<body\>/ui', '/\<\/body\>/ui'], '', $string);
        $string = $this->Utils->HtmlTrim($string);

        return $string;
    }
}
