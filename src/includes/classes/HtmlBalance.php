<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * HTML normalizing utilities.
 *
 * @since 150424 Initial release.
 */
class HtmlBalance extends AbsBase
{
    protected $PhpHas;
    protected $HtmlTrim;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(
        PhpHas $PhpHas,
        HtmlTrim $HtmlTrim
    ) {
        parent::__construct();

        $this->PhpHas   = $PhpHas;
        $this->HtmlTrim = $HtmlTrim;
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
        if (!$this->PhpHas->extension('dom')) {
            throw new \Exception('DOM extension missing.');
        }
        $dom = new \DOMDocument();
        $dom->loadHTML($html);

        $string = $dom->saveHTML($dom->getElementsByTagName('body')->item(0));
        $string = str_ireplace(array('<body>', '</body>'), '', $string);
        $string = $this->HtmlTrim($string);

        return $string;
    }
}
