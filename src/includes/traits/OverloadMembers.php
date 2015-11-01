<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits;

/**
 * Overload members.
 *
 * @since 150424 Initial release.
 */
trait OverloadMembers
{
    /**
     * Overload properties.
     *
     * @since 15xxxx Initial release.
     *
     * @type \stdClass Overload properties.
     */
    protected $overload;

    /**
     * Writable overload properties.
     *
     * @since 15xxxx Initial release.
     *
     * @type array Writable overload properties.
     */
    protected $writable_overload_properties;

    /**
     * Initialize overloads.
     *
     * @since 15xxxx Initial release.
     */
    protected function overloadInit()
    {
        $this->overload                     = new \stdClass();
        $this->writable_overload_properties = [];
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
    public function __isset(string $property): bool
    {
        return isset($this->overload->{$property});
    }

    /**
     * Magic/overload property getter.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $property Property to get.
     *
     * @throws Exception If the `$overload` property is undefined.
     *
     * @return mixed The value of `$this->overload{$property}`.
     *
     * @see http://php.net/manual/en/language.oop5.overloading.php
     */
    public function __get(string $property)
    {
        if (isset($this->overload->{$property}) || property_exists($this->overload, $property)) {
            return $this->overload->{$property};
        }
        throw new Exception(sprintf('Undefined overload property: `%1$s`.', $property));
    }

    /**
     * Magic/overload property setter.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $property Property to set.
     * @param mixed  $value    The value for this property.
     *
     * @see http://php.net/manual/en/language.oop5.overloading.php
     */
    public function __set(string $property, $value)
    {
        if (!isset($this->writable_overload_properties[$property])) {
            throw new Exception(sprintf('Refused to set overload property: `%1$s`.', $property));
        }
        $this->overload->{$_property} = $value;
    }

    /**
     * Magic `unset()` handler.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $property Property to unset.
     *
     * @see http://php.net/manual/en/language.oop5.overloading.php
     */
    public function __unset(string $property)
    {
        if (!isset($this->writable_overload_properties[$property])) {
            throw new Exception(sprintf('Refused to unset overload property: `%1$s`.', $property));
        }
        unset($this->overload->{$_property});
    }

    /**
     * Magic call handler.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $method Method to call upon.
     * @param array  $args   Arguments to pass to the method.
     *
     * @throws Exception If a class property matching the method does not exist.
     *
     * @see http://php.net/manual/en/language.oop5.overloading.php
     */
    public function __call(string $method, array $args = [])
    {
        if (isset($this->{$method}) && is_callable($this->{$method})) {
            return call_user_func_array($this->{$method}, $args);
        }
        if (isset($this->overload{$method}) && is_callable($this->overload{$method})) {
            return call_user_func_array($this->overload{$method}, $args);
        }
        throw new Exception(sprintf('Undefined method: `%1$s`.', $method));
    }

    /**
     * Setup overloads.
     *
     * @since 15xxxx Initial release.
     *
     * @param array|object $properties Property(s).
     *
     * @note This can be an associative array|object containing key/value pairs.
     *  Or, it can simply be a numeric array of existing properties to make read-only.
     *
     * @note Objects can be passed (always by reference) and each property value will be
     *  set by reference also. This allows for overload to contain nothing but references.
     *
     * @param bool $writable Overloaded properties are writable?
     */
    protected function overload($properties, bool $writable = false)
    {
        if (!is_array($properties) && !is_object($properties)) {
            throw new Exception(sprintf('Invalid type: `%1$s`.', gettype($properties)));
        }
        foreach ($properties as $_key_prop => &$_val_prop) {

            // Treat string keys as named property/value pairs.
            // Only nonexistent properties can be overloaded in this way.

            if (is_string($_key_prop)) {
                if (isset($_key_prop[0]) && !property_exists($this, $_key_prop)) {
                    $this->overload->{$_key_prop} = &$_val_prop;

                    if ($writable) { // Is the property writable?
                        $this->writable_overload_properties[$_key_prop] = -1;
                    } else { // Remove it otherwise; i.e., NOT writable.
                        unset($this->writable_overload_properties[$_key_prop]);
                    }
                }

            // Check each array value is an existing property name.
            //  Only existing properties can be overloaded in this way.
            //
            } elseif (is_string($_val_prop)) {
                if (isset($_val_prop[0]) && property_exists($this, $_val_prop)) {
                    $this->overload->{$_val_prop} = &$this->{$_val_prop};

                    if ($writable) { // Is the property writable?
                        $this->writable_overload_properties[$_val_prop] = -1;
                    } else { // Remove it otherwise; i.e., NOT writable.
                        unset($this->writable_overload_properties[$_val_prop]);
                    }
                }
            }
        } // unset($_key, $_value, $_property); // Housekeeping.
    }
}
