<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * Multibyte `ucwords()`.
 *
 * @since 15xxxx Enhancing multibyte support.
 */
class UcWords extends AbsBase
{
    /**
     * Class constructor.
     *
     * @since 15xxxx Enhancing multibyte support.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Multibyte `ucwords()`.
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
        return mb_convert_case($string, MB_CASE_TITLE);
    }
}
