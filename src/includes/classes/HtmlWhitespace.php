<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Traits;

/**
 * HTML whitespace utilities.
 *
 * @since 150424 Initial release.
 */
class HtmlWhitespace extends AbsBase
{
    use Traits\HtmlDefinitions;

    protected $Eols;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(
        Eols $Eols
    ) {
        parent::__construct();

        $this->Eols = $Eols;
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
    public function normalize($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->normalize($_value);
            }
            unset($_key, $_value); // Housekeeping.

            return $this->Eols->normalize($value);
        }
        $string = (string) $value;

        if (is_null($whitespace = &$this->staticKey(__FUNCTION__.'_whitespace'))) {
            $whitespace = implode('|', array_keys($this->DEF_HTML_WHITESPACE));
        }
        $string = preg_replace('/('.$whitespace.')('.$whitespace.')('.$whitespace.')+/i', '${1}${2}', $string);

        return $this->Eols->normalize($string);
    }
}
