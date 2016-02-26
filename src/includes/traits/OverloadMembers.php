<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;

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
     * @since 150424 Initial release.
     *
     * @type array Overload properties.
     */
    protected $¤¤overload = [];

    /**
     * Writable overload properties.
     *
     * @since 150424 Initial release.
     *
     * @type array Writable overload properties.
     */
    protected $¤¤writable_overload_properties = [];

    /**
     * Serialized properties.
     *
     * @since 150424 Initial release.
     *
     * @return string Serialized data.
     */
    public function serialize(): string
    {
        return serialize((object) $this->¤¤overload);
    }

    /**
     * Unserialize handler.
     *
     * @since 150424 Initial release.
     *
     * @param string $serialized Serialized data.
     *
     * @note \Serializable interface is missing `string` hint.
     */
    public function unserialize(/* string */$serialized)
    {
        $this->¤¤overload = (array) unserialize($serialized);
    }

    /**
     * JSON-encode properties.
     *
     * @since 150424 Initial release.
     *
     * @return mixed Properties to JSON-encode.
     */
    public function jsonSerialize()
    {
        return (object) $this->¤¤overload;
    }

    /**
     * Properties to dump.
     *
     * @since 150424 Initial release.
     *
     * @return array Properties to dump.
     */
    public function __debugInfo(): array
    {
        // This returns public properties only.
        $props = call_user_func('get_object_vars', $this);

        unset($props['Di']);
        unset($props['App']);
        unset($props['Utils']);

        return $props;
    }

    /**
     * Magic/overload `isset()` checker.
     *
     * @since 150424 Initial release.
     *
     * @param string $property Property to check.
     *
     * @return bool TRUE if `isset($this->¤¤overload{$property})`.
     *
     * @see http://php.net/manual/en/language.oop5.overloading.php
     */
    public function __isset(string $property): bool
    {
        return isset($this->¤¤overload[$property]);
    }

    /**
     * Magic/overload property getter.
     *
     * @since 150424 Initial release.
     *
     * @param string $property Property to get.
     *
     * @return mixed The value of `$this->¤¤overload{$property}`.
     *
     * @see http://php.net/manual/en/language.oop5.overloading.php
     */
    public function __get(string $property)
    {
        if (isset($this->¤¤overload[$property])) {
            return $this->¤¤overload[$property];
        } elseif (array_key_exists($property, $this->¤¤overload)) {
            return $this->¤¤overload[$property];
        }
        throw new Exception(sprintf('Undefined overload property: `%1$s`.', $property));
    }

    /**
     * Magic/overload property setter.
     *
     * @since 150424 Initial release.
     *
     * @param string $property Property to set.
     * @param mixed  $value    The value for this property.
     *
     * @see http://php.net/manual/en/language.oop5.overloading.php
     */
    public function __set(string $property, $value)
    {
        if (isset($this->¤¤writable_overload_properties[$property])) {
            if ($this->¤¤writable_overload_properties[$property] === 1) {
                $this->{$property} = $value; // Direct access.
                // See also: {@link overload()} below.
                return; // Null return value.
            } else {
                $this->¤¤overload[$property] = $value;
                return; // Null return value.
            }
        }
        throw new Exception(sprintf('Refused to set overload property: `%1$s`.', $property));
    }

    /**
     * Magic `unset()` handler.
     *
     * @since 150424 Initial release.
     *
     * @param string $property Property to unset.
     *
     * @see http://php.net/manual/en/language.oop5.overloading.php
     *
     * @WARNING Do not attempt to `unset()` an overloaded property. It can lead to confusion.
     *  Since overloads are by-reference, we need to prevent `unset()` at all times.
     *
     *  This way an overloaded property cannot be `unset()` (killing the reference);
     *  w/o killing the underlying value—which could lead to confusion.
     *  If that's needed, use `= null` instead please.
     */
    public function __unset(string $property)
    {
        throw new Exception(sprintf('Refused to unset overload property: `%1$s`.', $property));
    }

    /**
     * Magic call handler.
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
        throw new Exception(sprintf('Undefined overload method: `%1$s`.', $method));
    }

    /**
     * Setup overloads.
     *
     * @since 150424 Initial release.
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
                    $this->¤¤writable_overload_properties[$_property] = 1;
                    $this->{$_property}                               = null;
                    $this->{$_property}                               = &$_value;
                    $this->¤¤overload[$_property]                     = &$this->{$_property};
                } else { // Remove it otherwise; i.e., NOT writable.
                    unset($this->¤¤writable_overload_properties[$_property]);
                    $this->¤¤overload[$_property] = &$_value;
                }
            } // unset($_property, $_value); // Housekeeping.

            return; // All done here.
        }
        // Property names in an array can be overloaded for public access.
        // This only works if each property in the array already exists in this class.

        if (is_array($properties)) {
            foreach ($properties as $_key => $_property) {
                if (!property_exists($this, $_property)) {
                    throw new Exception(sprintf('Property: `%1$s` does not exist.', $_property));
                }
                if ($writable) { // Is the property writable?
                    $this->¤¤writable_overload_properties[$_property] = 2;
                    $this->¤¤overload[$_property]                     = &$this->{$_property};
                } else { // Remove it otherwise; i.e., NOT writable.
                    unset($this->¤¤writable_overload_properties[$_property]);
                    $this->¤¤overload[$_property] = &$this->{$_property};
                }
            } // unset($_key, $_property); // Housekeeping.

            return; // All done here.
        }
        throw new Exception(sprintf('Invalid type: `%1$s`; expecting object/array.', gettype($properties)));
    }
}
