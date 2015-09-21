<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * YAML utilities.
 *
 * @since 150424 Initial release.
 */
class Yaml extends AbsBase
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
            }
            unset($_key, $_value); // Housekeeping.

            return $value;
        }
        if (!($yaml = (string) $value)) {
            return []; // Empty.
        }
        try {
            $array = \Symfony\Component\Yaml\Yaml::parse($yaml);
        } catch (\Exception $exception) {
            $array = []; // Empty.
        }
        return is_array($array) ? $array : [];
    }
}
