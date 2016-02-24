<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * App utilities.
 *
 * @since 150424 Initial release.
 */
class AppUtils extends Core
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
        if (class_exists($class = $this->App->getClass(__NAMESPACE__.'\\Utils\\'.$property))) {
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
        } elseif (class_exists($class = $this->App->getClass(__NAMESPACE__.'\\Utils\\'.$method))) {
            $Utility = $this->App->Di->get($class, [], true);
            $this->overload((object) [$method => $Utility], true);
            return $Utility(...$args);
        }
        return parent::__call($property);
    }
}
