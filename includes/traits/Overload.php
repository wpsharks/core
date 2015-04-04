<?php
/**
 * Overload
 *
 * @since 15xxxx Initial release.
 * @copyright WebSharks, Inc. <http://www.websharks-inc.com>
 * @license GNU General Public License, version 3
 */
namespace WebSharks\Core\Traits
{
	/**
	 * Overload
	 *
	 * @since 15xxxx Initial release.
	 */
	trait Overload
	{
		/**
		 * @var array Overload properties.
		 * @since 15xxxx Initial release.
		 */
		protected $___overload = array();

		/**
		 * Magic/overload `isset()` checker.
		 *
		 * @since 15xxxx Initial release.
		 *
		 * @param string $property Property to check.
		 *
		 * @return boolean TRUE if `isset($this->___overload[$property])`.
		 *
		 * @see http://php.net/manual/en/language.oop5.overloading.php
		 */
		public function __isset($property)
		{
			$property = (string)$property; // Force string.

			return isset($this->___overload[$property]);
		}

		/**
		 * Magic/overload property getter.
		 *
		 * @since 15xxxx Initial release.
		 *
		 * @param string $property Property to get.
		 *
		 * @return mixed The value of `$this->___overload[$property]`.
		 *
		 * @throws \Exception If the `$___overload` property is undefined.
		 *
		 * @see http://php.net/manual/en/language.oop5.overloading.php
		 */
		public function __get($property)
		{
			$property = (string)$property; // Force string.

			if(array_key_exists($this->___overload, $property))
				return $this->___overload[$property];

			throw new \Exception(sprintf('Undefined overload property: `%1$s`.', $property));
		}

		/**
		 * Magic/overload property setter.
		 *
		 * @since 15xxxx Initial release.
		 *
		 * @param string $property Property to set.
		 * @param mixed  $value The value for this property.
		 *
		 * @throws \Exception We do NOT allow magic/overload properties to be set.
		 *    Magic/overload properties in this class are read-only.
		 *
		 * @see http://php.net/manual/en/language.oop5.overloading.php
		 */
		public function __set($property, $value)
		{
			$property = (string)$property; // Force string.

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
		 *    Magic/overload properties in this class are read-only.
		 *
		 * @see http://php.net/manual/en/language.oop5.overloading.php
		 */
		public function __unset($property)
		{
			$property = (string)$property; // Force string.

			throw new \Exception(sprintf('Refused to unset overload property: `%1$s`.', $property));
		}
	}
}