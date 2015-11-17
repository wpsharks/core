<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * Multibyte `strrev()`.
 *
 * @since 15xxxx Enhancing multibyte support.
 */
class StrRev extends AbsBase
{
    /**
     * Multibyte `strrev()`.
     *
     * @since 15xxxx Enhancing multibyte support.
     *
     * @param mixed $value Any input value.
     *
     * @return string|array|object Output value.
     */
    public function __invoke($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->__invoke($_value);
            } // unset($_key, $_value);

            return $value;
        }
        $string = (string) $value;

        if (!isset($string[0])) {
            return $string;
        }
        preg_match_all('/./us', $string, $m);

        return ($string = implode('', array_reverse($m[0])));
    }
}
