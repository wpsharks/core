<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * App utilities.
 *
 * @since 150424 Initial release.
 */
class AppUtils extends Classes\Core
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
        $class = __NAMESPACE__.$map['sub_namespace'].'\\Utils\\'.$map['prop_meth'];
        $class = $this->App->getClass($class);

        if (class_exists($class)) {
            $Utility = $this->App->Di->get($class, [], true);
            $this->overload((object) [$property => $Utility], true);
            return $Utility;
        }
        return parent::__get($property);
    }

    /**
     * Magic utility factory.
     *
     * @since 150424 Initial release.
     *
     * @param string $method Method to call upon.
     * @param array  $args   Arguments to pass to the method.
     *
     * @return mixed Overloaded method return value.
     *
     * @see http://php.net/manual/en/language.oop5.overloading.php
     */
    public function __call(string $method, array $args = [])
    {
        if (isset($this->¤¤overload[$method])) {
            return $this->¤¤overload[$method](...$args);
        }
        $map   = $this->map($method);
        $class = __NAMESPACE__.$map['sub_namespace'].'\\Utils\\'.$map['prop_meth'];
        $class = $this->App->getClass($class);

        if (class_exists($class)) {
            $Utility = $this->App->Di->get($class, [], true);
            $this->overload((object) [$method => $Utility], true);
            return $Utility(...$args);
        }
        return parent::__call($method);
    }

    /**
     * Map the sub-namespace.
     *
     * @since 160225 Adding utilities map.
     *
     * @param string $prop_meth Property (or method) to check.
     *
     * @return array `['sub_namespace' => '', 'prop_meth' => '']`.
     */
    protected function map(string $prop_meth): array
    {
        if (mb_strpos($prop_meth, '©') === 0) {
            return [
                'sub_namespace' => '\\Core',
                'prop_meth'     => mb_substr($prop_meth, 1),
            ];
        }
        foreach ($this->App->Config->©sub_namespace_map as $_identifier => $_sub_namespace) {
            if (mb_strpos($prop_meth, $_identifier) === 0) {
                return [
                    'sub_namespace' => '\\'.$_sub_namespace,
                    'prop_meth'     => mb_substr($prop_meth, mb_strlen($prop_meth)),
                ];
            }
        } // unset($_identifier, $_sub_namespace);

        return ['sub_namespace' => '', 'prop_meth' => $prop_meth];
    }
}
