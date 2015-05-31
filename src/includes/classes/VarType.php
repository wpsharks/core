<?php
namespace WebSharks\Core\Classes;

/**
 * Var type utilities.
 *
 * @since 150424 Initial release.
 */
class VarType extends AbsBase
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
     * Var typify deeply.
     *
     * @since 150424 Initial release.
     *
     * @param mixed  $value Any input value.
     * @param string $type  See <http://php.net/manual/en/function.settype.php>
     *
     * @return mixed|array|object Output value.
     */
    public function ify($value, $type)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->ify($_value, $type);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        $type = (string) $type;
        settype($value, $type);

        return $value;
    }
}
