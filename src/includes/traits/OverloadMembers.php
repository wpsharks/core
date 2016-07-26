<?php
/**
 * Overload members.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Traits;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

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
     * @var array Overload properties.
     */
    protected $x___overload = [];

    /**
     * Writable overload properties.
     *
     * @since 150424 Initial release.
     *
     * @var array Writable overload properties.
     */
    protected $x___writable_overload_properties = [];

    /**
     * Serialized properties.
     *
     * @since 150424 Initial release.
     *
     * @return string Serialized data.
     */
    public function serialize(): string
    {
        return serialize((object) $this->x___overload);
    }

    /**
     * Unserialize handler.
     *
     * @since 150424 Initial release.
     *
     * @param string $serialized Serialized data.
     *
     * @internal \Serializable interface is missing `string` hint.
     */
    public function unserialize(/* string */$serialized)
    {
        $this->x___overload = (array) unserialize($serialized);
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
        return (object) $this->x___overload;
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
        unset($props['Di'], $props['App'], $props['Utils']);

        return $props;
    }

    /**
     * Magic/overload `isset()` checker.
     *
     * @since 150424 Initial release.
     *
     * @param string $property Property to check.
     *
     * @return bool TRUE if `isset($this->x___overload{$property})`.
     *
     * @link http://php.net/manual/en/language.oop5.overloading.php
     */
    public function __isset(string $property): bool
    {
        if (!isset($property[1]) && ($property === 'c' || $property === 's' || $property === 'a' || $property === 'f')) {
            // NOTE: `f` comes last here, because it improves the speed at which a match occurs.
            return true; // Public read-only access.
        } else {
            return isset($this->x___overload[$property]);
        }
    }

    /**
     * Magic/overload property getter.
     *
     * @since 150424 Initial release.
     *
     * @param string $property Property to get.
     *
     * @return mixed The value of `$this->x___overload{$property}`.
     *
     * @link http://php.net/manual/en/language.oop5.overloading.php
     */
    public function __get(string $property)
    {
        if (!isset($property[1]) && ($property === 'c' || $property === 's' || $property === 'a' || $property === 'f')) {
            // NOTE: `f` comes last here, because it improves the speed at which a match occurs.
            return $this->{$property}; // Public read-only access.
            //
        } elseif (isset($this->x___overload[$property])) {
            return $this->x___overload[$property];
        } elseif (array_key_exists($property, $this->x___overload)) {
            return $this->x___overload[$property];
        }
        throw $this->c::issue([], sprintf('Undefined overload property: `%1$s`.', $property), __METHOD__);
    }

    /**
     * Magic/overload property setter.
     *
     * @since 150424 Initial release.
     *
     * @param string $property Property to set.
     * @param mixed  $value    The value for this property.
     *
     * @link http://php.net/manual/en/language.oop5.overloading.php
     */
    public function __set(string $property, $value)
    {
        if (isset($this->x___writable_overload_properties[$property])) {
            if ($this->x___writable_overload_properties[$property] === 1) {
                $this->{$property} = $value; // Direct public write access.
                // See also: {@link overload()} below for details.
                return; // Null return value.
            } else {
                $this->x___overload[$property] = $value;
                // This is indirect public write access.
                return; // Null return value.
            }
        }
        throw $this->c::issue([], sprintf('Refused to set overload property: `%1$s`.', $property), __METHOD__);
    }

    /**
     * Magic `unset()` handler.
     *
     * @since 150424 Initial release.
     *
     * @param string $property Property to unset.
     *
     * @link http://php.net/manual/en/language.oop5.overloading.php
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
        throw $this->c::issue([], sprintf('Refused to unset overload property: `%1$s`.', $property), __METHOD__);
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
     * @link http://php.net/manual/en/language.oop5.overloading.php
     */
    public function __call(string $method, array $args = [])
    {
        if (isset($this->x___overload[$method])) {
            return $this->x___overload[$method](...$args);
        }
        throw $this->c::issue([], sprintf('Undefined overload method: `%1$s`.', $method), __METHOD__);
    }

    /**
     * Is class overloaded?
     *
     * @since 160515 Overload conditional.
     *
     * @return bool True if class is overloaded.
     */
    protected function isOverloaded(): bool
    {
        return !empty($this->x___overload);
    }

    /**
     * Current overload array.
     *
     * @since 160515 Overload conditional.
     *
     * @return array Current overload array.
     */
    protected function overloadArray(): array
    {
        return $this->x___overload;
    }

    /**
     * Writable overload properties.
     *
     * @since 160515 Overload conditional.
     *
     * @return array Writable overload properties.
     */
    protected function writableOverloadProperties(): array
    {
        return array_keys($this->x___writable_overload_properties);
    }

    /**
     * Setup overloads.
     *
     * @since 150424 Initial release.
     *
     * @param array|object $properties Property(s).
     *
     * @internal Objects are passed by reference, so we can overload properties by reference.
     *   This only works if each property in the object does not yet exist in this class.
     *   Object properties can be set as writable or not writable.
     *
     * @internal Property names in an array can be overloaded; e.g., to provide public or JSON access.
     *   This only works if each property in the array already exists in this class.
     *   Overloaded object property names can be set as writable or not writable.
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
        // Object properties can be set as writable or not writable.

        if (is_object($properties)) {
            foreach ($properties as $_property => &$_value) {
                if (property_exists($this, $_property)) {
                    throw $this->c::issue(sprintf('Property: `%1$s` exists already.', $_property));
                }
                if ($writable) { // Is the property writable?
                    $this->x___writable_overload_properties[$_property] = 1;
                    $this->{$_property}                                 = null;
                    $this->{$_property}                                 = &$_value;
                    $this->x___overload[$_property]                     = &$this->{$_property};
                } else { // Remove it otherwise; i.e., NOT writable.
                    unset($this->x___writable_overload_properties[$_property]);
                    $this->x___overload[$_property] = &$_value;
                }
            } // unset($_property, $_value); // Housekeeping.

            return; // All done here.
        }
        // Property names in an array can be overloaded; e.g., to provide public or JSON access.
        // This only works if each property in the array already exists in this class.
        // Overloaded object property names can be set as writable or not writable.

        if (is_array($properties)) {
            foreach ($properties as $_key => $_property) {
                if (!property_exists($this, $_property)) {
                    throw $this->c::issue(sprintf('Property: `%1$s` does not exist.', $_property));
                }
                if ($writable) { // Is the property writable?
                    $this->x___writable_overload_properties[$_property] = 0;
                    $this->x___overload[$_property]                     = &$this->{$_property};
                } else { // Remove it otherwise; i.e., NOT writable.
                    unset($this->x___writable_overload_properties[$_property]);
                    $this->x___overload[$_property] = &$this->{$_property};
                }
            } // unset($_key, $_property); // Housekeeping.

            return; // All done here.
        }
        throw $this->c::issue(sprintf('Invalid type: `%1$s`; expecting object/array.', gettype($properties)));
    }
}
