<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;

/**
 * Var type utilities.
 *
 * @since 150424 Initial release.
 */
class VarType extends Classes\AbsBase
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
    public function ify($value, string $type)
    {
        if ((is_array($value) || is_object($value))
            // If converting to array|object, do not iterate.
            && !in_array($type, ['array', 'object'], true)) {
            // e.g., allow array to become an object.

            foreach ($value as $_key => &$_value) {
                $_value = $this->ify($_value, $type);
            } // unset($_key, $_value);

            return $value;
        }
        settype($value, $type);

        return $value;
    }
}
