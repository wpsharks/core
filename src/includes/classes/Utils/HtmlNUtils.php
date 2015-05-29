<?php
namespace WebSharks\Core\Classes\Utils;

/**
 * HTML normalizing utilities.
 *
 * @since 150424 Initial release.
 */
class HtmlNUtils extends AbsBase
{
    abstract public function eolsN($value);
    abstract public function &staticKey($function, $args = array());

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
     * Normalizes HTML whitespace deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value Any input value.
     *
     * @return string|array|object With normalized HTML whitespace deeply.
     */
    public function htmlNWhitespace($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->htmlNWhitespace($_value);
            }
            unset($_key, $_value); // Housekeeping.

            return $this->eolsN($value);
        }
        $string = (string) $value;

        if (is_null($whitespace = &$this->staticKey(__FUNCTION__.'_whitespace'))) {
            $whitespace = implode('|', array_keys($this->def_html_whitespace));
        }
        $string = preg_replace('/('.$whitespace.')('.$whitespace.')('.$whitespace.')+/i', '${1}${2}', $string);

        return $this->eolsN($string);
    }
}
