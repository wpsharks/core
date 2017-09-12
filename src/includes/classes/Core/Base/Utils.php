<?php
/**
 * Utilities.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Base;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Utilities.
 *
 * @since 150424 Initial release.
 */
class Utils extends Classes\Core\Base\Core
{
    /**
     * Magic utility factory.
     *
     * @since 150424 Initial release.
     *
     * @param string $property Property.
     *
     * @return mixed Overloaded property value.
     */
    public function __get(string $property)
    {
        $map   = $this->map($property);
        $class = Classes::class.$map['sub_namespace'].'\\Utils\\'.$map['key'];
        $class = $this->App->getClass($class);

        if (class_exists($class)) {
            $Util = $this->App->Di->get($class, [], true);
            $this->overload((object) [$property => $Util], true);
            return $Util;
        }
        return parent::__get($property);
    }

    /**
     * Magic utility factory.
     *
     * @since 150424 Initial release.
     *
     * @param string $method Method to call upon.
     * @param array  $args   Arguments to pass.
     *
     * @return mixed Overloaded method return value.
     */
    public function __call(string $method, array $args = [])
    {
        if (isset($this->x___overload_data[$method])) {
            return $this->x___overload_data[$method](...$args);
        }
        $map   = $this->map($method);
        $class = Classes::class.$map['sub_namespace'].'\\Utils\\'.$map['key'];
        $class = $this->App->getClass($class);

        if (class_exists($class)) {
            $Util = $this->App->Di->get($class, [], true);
            $this->overload((object) [$method => $Util], true);
            return $Util(...$args);
        }
        return parent::__call($method, $args);
    }

    /**
     * Map the sub-namespace.
     *
     * @since 160225 Adding utilities map.
     *
     * @param string $key Property or method.
     *
     * @return array `['sub_namespace' => '', 'key' => '']`.
     */
    protected function map(string $key): array
    {
        if (mb_strpos($key, '©') === 0) {
            return [
                'sub_namespace' => '\\Core',
                'key'           => mb_substr($key, 1),
            ];
        }
        foreach ($this->App->Config->©sub_namespace_map as $_sub_namespace => $_identifiers) {
            if ($_sub_namespace && !empty($_identifiers['©utils']) && mb_strpos($key, $_identifiers['©utils']) === 0) {
                return [
                    'sub_namespace' => '\\'.$_sub_namespace,
                    'key'           => mb_substr($key, mb_strlen($_identifiers['©utils'])),
                ];
            }
        } // unset($_sub_namespace, $_identifiers);

        return ['sub_namespace' => '', 'key' => $key];
    }
}
