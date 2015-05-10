<?php
namespace WebSharks\Core\Traits;

/**
 * Var type utilities.
 *
 * @since 150424 Initial release.
 */
trait VarTypeUtils
{
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
    protected function varTypify($value, $type)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->varTypify($_value, $type);
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        $type = (string) $type;
        settype($value, $type);

        return $value;
    }
}
