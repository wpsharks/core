<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Classes\AppUtils;
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
     * App namespace.
     *
     * @since 15xxxx
     *
     * @type string
     */
    protected $app_ns;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(App $App)
    {
        parent::__construct();

        $this->App    = $App; // And parse namespace.
        $this->app_ns = mb_strrchr(get_class($this->App), '\\', true);
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
        if (class_exists($utility = $this->app_ns.'\\AppUtils\\'.$property)) {
            $utility = $this->App->Di->get($utility);
            $this->overload((object) [$property => $utility], true);
            return $utility;
        } elseif (class_exists($utility = __NAMESPACE__.'\\AppUtils\\'.$property)) {
            $utility = $this->App->Di->get($utility);
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
        } elseif (class_exists($utility = $this->app_ns.'\\AppUtils\\'.$method)) {
            $utility = $this->App->Di->get($utility);
            $this->overload((object) [$method => $utility], true);
            return $utility(...$args);
        } elseif (class_exists($utility = __NAMESPACE__.'\\AppUtils\\'.$method)) {
            $utility = $this->App->Di->get($utility);
            $this->overload((object) [$method => $utility], true);
            return $utility(...$args);
        }
        return parent::__call($property);
    }
}
