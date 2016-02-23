<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use Symfony\Component\Yaml\Yaml as Parser;

/**
 * YAML utilities.
 *
 * @since 150424 Initial release.
 */
class Yaml extends Classes\Core
{
    /**
     * YAML parser.
     *
     * @since 150424 Initial release.
     *
     * @param mixed $value Any input value.
     *
     * @return array|object Converted value(s).
     */
    public function parse($value)
    {
        if (is_array($value) || is_object($value)) {
            foreach ($value as $_key => &$_value) {
                $_value = $this->parse($_value, $args);
            } // unset($_key, $_value);

            return $value;
        }
        if (!($yaml = (string) $value)) {
            return []; // Empty.
        }
        try {
            $array = Parser::parse($yaml);
        } catch (\Throwable $Exception) {
            $array = []; // Empty.
        }
        return is_array($array) ? $array : [];
    }
}
