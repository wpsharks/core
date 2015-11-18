<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * App utilities.
 *
 * @since 15xxxx Initial release.
 */
class AppUtils extends AbsCore
{
    /**
     * Dicer.
     *
     * @since 15xxxx
     *
     * @type AppDi
     */
    protected $Di;

    /**
     * App.
     *
     * @since 15xxxx
     *
     * @type AbsApp
     */
    protected $App;

    /**
     * App class.
     *
     * @since 15xxxx
     *
     * @type string
     */
    protected $app_class;

    /**
     * App namespace.
     *
     * @since 15xxxx
     *
     * @type string
     */
    protected $app_namespace;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(AbsApp $App)
    {
        parent::__construct();

        $this->App = $App;
        $this->Di  = $App->Di;

        $this->app_class     = get_class($this->App);
        $this->app_namespace = mb_strrchr($this->app_class, '\\', true);
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
        if (class_exists($utility = $this->app_namespace.'\\AppUtils\\'.$property)) {
            $utility = $this->Di->get($utility);
            $this->overload([$property => $utility], true);
            return $utility;
        } elseif (class_exists($utility = __NAMESPACE__.'\\AppUtils\\'.$property)) {
            $utility = $this->Di->get($utility);
            $this->overload([$property => $utility], true);
            return $utility;
        }
        return parent::__get($property);
    }
}
