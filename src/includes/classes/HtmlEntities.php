<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * HTML escape utilities.
 *
 * @since 150424 Initial release.
 */
class HtmlEntities extends AbsBase
{
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
     * Escape HTML markup deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value         Any input value.
     * @param bool  $double_encode Encode existing entities? Defaults to `FALSE`.
     * @param int   $flags         Optional. Defaults to `ENT_HTML5 | ENT_QUOTES | ENT_SUBSTITUTE`.
     *
     * @return mixed Value after having been escaped deeply.
     */
    public function encode($value, $double_encode = false, $flags = null)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->encode($_value, $double_encode, $flags);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        $string = (string) $value;

        if (!isset($flags)) {
            $flags = ENT_HTML5 | ENT_QUOTES | ENT_SUBSTITUTE;
        }
        return htmlspecialchars($string, $flags, 'UTF-8', $double_encode);
    }

    /**
     * Unescape HTML markup deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value Any input value.
     * @param int   $flags Optional. Defaults to `ENT_HTML5 | ENT_QUOTES`.
     *
     * @return mixed Value after having been unescaped deeply.
     */
    public function decode($value, $flags = null)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->decode($_value, $flags);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        $string = (string) $value;

        if (!isset($flags)) {
            $flags = ENT_HTML5 | ENT_QUOTES;
        }
        return html_entity_decode($string, $flags, 'UTF-8');
    }
}
