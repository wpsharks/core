<?php
namespace WebSharks\Core\Traits;

/**
 * Overload members.
 *
 * @since 150424 Initial release.
 */
trait OverloadMembers
{
    /**
     * @type \stdClass Overload properties.
     *
     * @since 15xxxx Initial release.
     */
    protected $overload;

    /**
     * Initialize overloads.
     *
     * @since 15xxxx Initial release.
     */
    protected function overloadInit($properties)
    {
        $this->overload = new \stdClass();
    }

    /**
     * Setup overloads.
     *
     * @since 15xxxx Initial release.
     *
     * @param string|array $properties Property(s).
     */
    protected function overload($properties)
    {
        foreach ((array) $properties as $_key => $_property) {
            $this->overload->{$_property} = &$this->{$_property};
        }
        unset($_key, $_property); // Housekeeping.
    }

    /**
     * Magic/overload `isset()` checker.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $property Property to check.
     *
     * @return bool TRUE if `isset($this->overload{$property})`.
     *
     * @see http://php.net/manual/en/language.oop5.overloading.php
     */
    public function __isset($property)
    {
        $property = (string) $property; // Force string.

        return isset($this->overload->{$property});
    }

    /**
     * Magic/overload property getter.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $property Property to get.
     *
     * @throws \Exception If the `$overload` property is undefined.
     *
     * @return mixed The value of `$this->overload{$property}`.
     *
     * @see http://php.net/manual/en/language.oop5.overloading.php
     */
    public function __get($property)
    {
        $property = (string) $property; // Force string.

        if (property_exists($this->overload, $property)) {
            return $this->overload->{$property};
        }
        throw new \Exception(sprintf('Undefined overload property: `%1$s`.', $property));
    }

    /**
     * Magic/overload property setter.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $property Property to set.
     * @param mixed  $value    The value for this property.
     *
     * @throws \Exception We do NOT allow magic/overload properties to be set.
     *                    Magic/overload properties in this class are read-only.
     *
     * @see http://php.net/manual/en/language.oop5.overloading.php
     */
    public function __set($property, $value)
    {
        $property = (string) $property; // Force string.

        throw new \Exception(sprintf('Refused to set overload property: `%1$s`.', $property));
    }

    /**
     * Magic `unset()` handler.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $property Property to unset.
     *
     * @throws \Exception We do NOT allow magic/overload properties to be unset.
     *                    Magic/overload properties in this class are read-only.
     *
     * @see http://php.net/manual/en/language.oop5.overloading.php
     */
    public function __unset($property)
    {
        $property = (string) $property; // Force string.

        throw new \Exception(sprintf('Refused to unset overload property: `%1$s`.', $property));
    }

    /**
     * Magic call handler.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $method Method to call upon.
     * @param array  $args   Arguments to pass to the method.
     *
     * @throws \Exception If a class property matching the method does not exist.
     *
     * @see http://php.net/manual/en/language.oop5.overloading.php
     */
    public function __call($method, array $args = [])
    {
        $method = (string) $method; // Force string.

        if (isset($this->{$method}) && is_callable($this->{$method})) {
            return call_user_func_array($this->{$method}, $args);
        }
        if (isset($this->overload{$method}) && is_callable($this->overload{$method})) {
            return call_user_func_array($this->overload{$method}, $args);
        }
        throw new \Exception(sprintf('Undefined method: `%1$s`.', $method));
    }
}
