<?php
/**
 * Overload members.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Traits;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
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
     * Overload data.
     *
     * @since 17xxxx Initial release.
     *
     * @type array Overload data.
     */
    protected $x___overload_data = [];

    /**
     * Writable overload keys.
     *
     * @since 150424 Initial release.
     *
     * @type array Writable overload keys.
     */
    protected $x___writable_overload_keys = [];

    /**
     * Serialized properties.
     *
     * @since 150424 Initial release.
     *
     * @return string Serialized data.
     */
    public function serialize(): string
    {
        return serialize((object) $this->overloadPropertyData());
    }

    /**
     * Unserialize handler.
     *
     * @since 150424 Initial release.
     *
     * @param string $serialized Serialized data.
     */
    public function unserialize($serialized)
    {
        $this->overload((object) unserialize($serialized), true);
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
        return (object) $this->overloadPropertyData();
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
        $properties = call_user_func('get_object_vars', $this);
        unset($properties['Di'], $properties['App'], $properties['Utils']);
        return $properties; // To include in debug.
    }

    /**
     * Magic/overload checker.
     *
     * @since 150424 Initial release.
     *
     * @param string $property Property key.
     *
     * @return bool True if non-null.
     */
    public function __isset(string $property): bool
    {
        if (!isset($property[1]) && ($property === 'c' || $property === 's' || $property === 'a' || $property === 'f')) {
            // NOTE: `f` comes last here, because it improves the speed at which a match occurs.
            return true; // Public read-only access.
        } else {
            return isset($this->x___overload_data[$property]);
        }
    }

    /**
     * Magic/overload properties.
     *
     * @since 150424 Initial release.
     *
     * @param string $property Property key.
     *
     * @return mixed Property value.
     */
    public function __get(string $property)
    {
        if (!isset($property[1]) && ($property === 'c' || $property === 's' || $property === 'a' || $property === 'f')) {
            // NOTE: `f` comes last because it improves the speed at which a match occurs.
            return $this->{$property}; // Public read-only access.
            //
        } elseif (isset($this->x___overload_data[$property])) {
            return $this->x___overload_data[$property];
            //
        } elseif (array_key_exists($property, $this->x___overload_data)) {
            return $this->x___overload_data[$property];
        }
        throw $this->c::issue([], sprintf('Undefined `%1$s` property.', $property), __METHOD__);
    }

    /**
     * Magic/overload property setter.
     *
     * @since 150424 Initial release.
     *
     * @param string $property Property key.
     * @param mixed  $value    Property value.
     */
    public function __set(string $property, $value)
    {
        if (isset($this->x___writable_overload_keys[$property])) {
            if ($this->x___writable_overload_keys[$property] === 1) {
                $this->{$property} = $value;
            } else {
                $this->x___overload_data[$property] = $value;
            }
            return; // Done here.
        }
        throw $this->c::issue([], sprintf('Refused to set `%1$s` property.', $property), __METHOD__);
    }

    /**
     * Magic unset handler.
     *
     * @since 150424 Initial release.
     *
     * @param string $property Property key.
     *
     * @internal Note: This intentionally does NOT unset `$this->{$property}`,
     * as that would allow direct public write access to a read-only property.
     * ---
     * @internal If unsetting a property that was given direct public write access,
     * calling `unset()` will not trigger this method, as it unsets the real property.
     * For that reason, if unsetting an overloaded property with direct public write access,
     * you must call `unset()` twice; i.e., calling a second time unsets the overload reference.
     */
    public function __unset(string $property)
    {
        unset($this->x___overload_data[$property]);
        unset($this->x___writable_overload_keys[$property]);
    }

    /**
     * Magic call handler.
     *
     * @since 150424 Initial release.
     *
     * @param string $method Method key/name.
     * @param array  $args   Arguments to pass.
     *
     * @return mixed Overloaded method return value.
     */
    public function __call(string $method, array $args = [])
    {
        if (isset($this->x___overload_data[$method])) {
            return $this->x___overload_data[$method](...$args);
        }
        throw $this->c::issue([], sprintf('Undefined `%1$s` method.', $method), __METHOD__);
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
        return !empty($this->x___overload_data);
    }

    /**
     * Current overload data.
     *
     * @since 17xxxx Overload conditional.
     *
     * @return array Current overload data.
     */
    protected function overloadData(): array
    {
        foreach ($this->x___overload_data as $_key => $_value) {
            $properties[$_key] = $_value;
        } // unset($_key, $_value);

        return $properties ?? [];
    }

    /**
     * All overload keys.
     *
     * @since 17xxxx Overload utils.
     *
     * @return array All overload keys.
     */
    protected function overloadKeys(): array
    {
        return array_keys($this->overloadData());
    }

    /**
     * Overload property data.
     *
     * @since 17xxxx Enhance overload utils.
     *
     * @return array Overload property data.
     */
    protected function overloadPropertyData(): array
    {
        foreach ($this->x___overload_data as $_key => $_value) {
            if (!is_callable($_value)) {
                $properties[$_key] = $_value;
            }
        } // unset($_key, $_value);

        return $properties ?? [];
    }

    /**
     * Writable overload keys.
     *
     * @since 17xxxx Overload utils.
     *
     * @return array Writable overload keys.
     */
    protected function overloadPropertyKeys(): array
    {
        return array_keys($this->overloadPropertyData());
    }

    /**
     * Writable overload property data.
     *
     * @since 17xxxx Enhance overload utils.
     *
     * @return array Writable overload property data.
     */
    protected function writableOverloadPropertyData(): array
    {
        foreach ($this->x___overload_data as $_key => $_value) {
            if (isset($this->x___writable_overload_keys[$_key]) && !is_callable($_value)) {
                $properties[$_key] = $_value;
            }
        } // unset($_key, $_value);

        return $properties ?? [];
    }

    /**
     * Writable overload property keys.
     *
     * @since 17xxxx Overload utils.
     *
     * @return array Writable overload property keys.
     */
    protected function writableOverloadPropertyKeys(): array
    {
        return array_keys($this->writableOverloadPropertyData());
    }

    /**
     * Setup overloads.
     *
     * @since 150424 Initial release.
     *
     * @param array|object $properties Property(s).
     *
     * @internal This will overload properties by reference.
     *   Object properties can be set as writable or not writable.
     *   -
     * @internal Property names can be overloaded to provide public or JSON access.
     *   Overloaded object property names can be set as writable or not writable.
     *
     * @param bool $writable Overloaded properties are writable?
     */
    protected function overload($properties, bool $writable = false)
    {
        if (is_object($properties)) {
            foreach ($properties as $_property => &$_value) {
                if ($writable) {
                    unset($this->{$_property});
                    unset($this->x___overload_data[$_property]);
                    $this->x___writable_overload_keys[$_property] = 1;
                    $this->{$_property}                           = null;
                    $this->{$_property}                           = &$_value;
                    $this->x___overload_data[$_property]          = &$this->{$_property};
                } else { // NOT writable.
                    unset($this->{$_property});
                    unset($this->x___overload_data[$_property]);
                    unset($this->x___writable_overload_keys[$_property]);
                    $this->x___overload_data[$_property] = &$_value;
                }
            } // unset($_property, $_value);
            return; // Stop now; all done here.
            //
        } elseif (is_array($properties)) {
            foreach ($properties as $_key => $_property) {
                if (!property_exists($this, $_property)) {
                    throw $this->c::issue([], sprintf('No `%1$s` property.', $_property), __METHOD__);
                }
                if ($writable) {
                    unset($this->x___overload_data[$_property]);
                    $this->x___writable_overload_keys[$_property] = -1;
                    $this->x___overload_data[$_property]          = &$this->{$_property};
                } else { // NOT writable.
                    unset($this->x___overload_data[$_property]);
                    unset($this->x___writable_overload_keys[$_property]);
                    $this->x___overload_data[$_property] = &$this->{$_property};
                }
            } // unset($_key, $_property);
            return; // Stop now; all done here.
        }
        throw $this->c::issue([], sprintf('`%1$s`; expecting object/array.', gettype($properties)), __METHOD__);
    }
}
