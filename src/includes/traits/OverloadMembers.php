<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits;

use WebSharks\Core\Classes\Exception;

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
     * Serialized properties.
     *
     * @since 15xxxx Initial release.
     *
     * @return string Serialized data.
     */
    public function serialize(): string
    {
        return serialize($this->overload);
    }

    /**
     * Unserialize handler.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $serialized Serialized data.
     *
     * @note \Serializable interface is missing `string` hint.
     */
    public function unserialize(/* string */$serialized)
    {
        $this->overload = unserialize($serialized);
    }

    /**
     * JSON-encode properties.
     *
     * @since 15xxxx Initial release.
     *
     * @return mixed Properties to JSON-encode.
     */
    public function jsonSerialize()
    {
        return $this->overload;
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
        if (isset($this->overload->{$property})) {
            return $this->overload->{$property};
        } elseif (property_exists($this->overload, $property)) {
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
        if (isset($this->writable_overload_properties[$property])) {
            if ($this->writable_overload_properties[$property] === -1) {
                $this->{$property} = $value; // Direct access.
                return; // Null return value.
            }
            $this->overload->{$property} = $value;
            return; // Null return value.
        }
        throw new Exception(sprintf('Refused to set overload property: `%1$s`.', $property));
    }

    /**
     * Magic `unset()` handler.
     *
     * @since 15xxxx Initial release.
     *
     * @param string $property Property to unset.
     *
     * @see http://php.net/manual/en/language.oop5.overloading.php
     *
     * @WARNING Do not attempt to `unset()` an overloaded property. It can lead to confusion.
     *  See also: the comments below for further details regarding this quirk.
     */
    public function __unset(string $property)
    {
        if (isset($this->writable_overload_properties[$property])) {
            // Since overloads are by-reference, we need to prevent `unset()` here.
            // This way an overloaded property cannot be `unset()` (killing the reference);
            // w/o killing the underlying value—which could lead to confusion.
            // Instead of calling `unset()`, set the value to `null`.
            throw new Exception(sprintf('Refused to unset overload property: `%1$s`. Please use `= null` instead.', $property));
            // WARNING: This exception will not be seen for writable properties, so use w/ caution!
        }
        throw new Exception(sprintf('Refused to unset overload property: `%1$s`.', $property));
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
     * @note Objects are passed by reference, so we can overload properties by reference.
     *   This only works if each property in the object does not yet exist in this class.
     *
     * @note Property names in an array can be overloaded for public access.
     *   This only works if each property in the array already exists in this class.
     *
     * @param bool $writable Overloaded properties are writable?
     *
     * @WARNING Do not attempt to `unset()` an overloaded property. It can lead to confusion.
     *  See also {@link __unset()} for further details regarding this quirk.
     */
    protected function overload($properties, bool $writable = false)
    {
        // Objects are passed by reference, so we can overload properties by reference.
        // This only works if each property in the object does not yet exist in this class.

        if (is_object($properties)) {
            foreach ($properties as $_property => &$_value) {
                if (property_exists($this, $_property)) {
                    throw new Exception(sprintf('Property: `%1$s` exists already.', $_property));
                }
                if ($writable) { // Is the property writable?
                    $this->writable_overload_properties[$_property] = -1;
                    $this->{$_property}                             = null;
                    $this->{$_property}                             = &$_value;
                    $this->overload->{$_property}                   = &$this->{$_property};
                } else { // Remove it otherwise; i.e., NOT writable.
                    unset($this->writable_overload_properties[$_property]);
                    $this->overload->{$_property} = &$_value;
                }
            } // unset($_property, $_value); // Housekeeping.

            return; // All done here.
        }
        // Property names in an array can be overloaded for public access.
        // This only works if each property in the array already exists in this class.

        if (is_array($properties)) {
            foreach ($properties as $_key => $_property) {
                if (!$_property || !is_string($_property) || !property_exists($this, $_property)) {
                    throw new Exception(sprintf('Property: `%1$s` does not exist.', $_property));
                }
                if ($writable) { // Is the property writable?
                    $this->writable_overload_properties[$_property] = -2;
                    $this->overload->{$_property}                   = &$this->{$_property};
                } else { // Remove it otherwise; i.e., NOT writable.
                    unset($this->writable_overload_properties[$_property]);
                    $this->overload->{$_property} = &$this->{$_property};
                }
            } // unset($_key, $_property); // Housekeeping.

            return; // All done here.
        }
        throw new Exception(sprintf('Invalid type: `%1$s`; expecting object/array.', gettype($properties)));
    }
}
