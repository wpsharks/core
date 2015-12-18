<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * App utilities.
 *
 * @since 15xxxx Initial release.
 */
class AppUtils extends AbsCore
{
    /**
     * App.
     *
     * @since 15xxxx
     *
     * @type App
     */
    protected $App;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        parent::__construct();

        $this->App = $GLOBALS[App::class];
    }

    /**
     * Magic utility factory.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $property Property.
     *
     * @return mixed Overloaded property value.
     */
    public function __get(string $property)
    {
        if (class_exists($this->App->namespace.'\\Utils\\'.$property)) {
            $utility = $this->App->Di->get($this->App->namespace.'\\Utils\\'.$property);
            $this->overload((object) [$property => $utility], true);
            return $utility;
        } elseif (class_exists(__NAMESPACE__.'\\Utils\\'.$property)) {
            $utility = $this->App->Di->get(__NAMESPACE__.'\\Utils\\'.$property);
            $this->overload((object) [$property => $utility], true);
            return $utility;
        }
        return parent::__get($property);
    }

    /**
     * Magic utility factory.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $method Method to call upon.
     * @param array  $args   Arguments to pass to the method.
     *
     * @see http://php.net/manual/en/language.oop5.overloading.php
     */
    public function __call(string $method, array $args = [])
    {
        if (isset($this->¤¤overload[$method])) {
            return $this->¤¤overload[$method](...$args);
        } elseif (class_exists($this->App->namespace.'\\Utils\\'.$method)) {
            $utility = $this->App->Di->get($this->App->namespace.'\\Utils\\'.$method);
            $this->overload((object) [$method => $utility], true);
            return $utility(...$args);
        } elseif (class_exists(__NAMESPACE__.'\\Utils\\'.$method)) {
            $utility = $this->App->Di->get(__NAMESPACE__.'\\Utils\\'.$method);
            $this->overload((object) [$method => $utility], true);
            return $utility(...$args);
        }
        return parent::__call($property);
    }
}
