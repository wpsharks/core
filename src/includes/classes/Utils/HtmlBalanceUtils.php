<?php
namespace WebSharks\Core\Classes\Utils;

/**
 * HTML normalizing utilities.
 *
 * @since 150424 Initial release.
 */
class HtmlBalanceUtils extends AbsBase
{
    abstract public function envHasExtension($extension);
    abstract public function htmlTrim($value, $chars = '', $extra_chars = '', $side = '');

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Balance HTML markup/tags.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value Any input value.
     *
     * @throws \Exception If missing DOM extension.
     *
     * @return string|array|object Balanced value (i.e., HTML markup).
     *
     * @note This works with HTML fragments only. No on a full document.
     * i.e., If the input contains `</html>` or `</body>` it is left as-is.
     */
    public function htmlBalanceTags($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->htmlBalanceTags($_value);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        if (!($string = (string) $value)) {
            return $string;
        }
        if (stripos($string, '</html>') !== false) {
            return $string;
        }
        if (stripos($string, '</body>') !== false) {
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
        if (!$this->envHasExtension('dom')) {
            throw new \Exception('DOM extension missing.');
        }
        $dom = new \DOMDocument();
        $dom->loadHTML($html);

        $string = $dom->saveHTML($dom->getElementsByTagName('body')->item(0));
        $string = str_ireplace(array('<body>', '</body>'), '', $string);
        $string = $this->htmlTrim($string);

        return $string;
    }
}
