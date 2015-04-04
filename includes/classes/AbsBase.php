<?php
/**
 * Base Abstraction
 *
 * @since 15xxxx Initial release.
 * @copyright WebSharks, Inc. <http://www.websharks-inc.com>
 * @license GNU General Public License, version 3
 */
namespace WebSharks\Core\Classes
{
	/**
	 * Base Abstraction
	 *
	 * @since 15xxxx Initial release.
	 */
	abstract class AbsBase
	{
		/*
		 * Cache Properties
		 */

		/**
		 * @var array Instance cache.
		 * @since 15xxxx Initial release.
		 */
		protected $cache = array();

		/**
		 * @var array Global static cache ref.
		 * @since 15xxxx Initial release.
		 */
		protected $static = array();

		/**
		 * @var array Global static cache.
		 * @since 15xxxx Initial release.
		 */
		protected static $___static = array();

		/*
		 * Constructor
		 */

		/**
		 * Class constructor.
		 *
		 * @since 15xxxx Initial release.
		 */
		public function __construct()
		{
			$class = get_called_class();

			if(empty(static::$___static[$class]))
				static::$___static[$class] = array();
			$this->static = &static::$___static[$class];
		}

		/*
		 * Cache Key Helpers
		 */

		/**
		 * Construct and acquire a cache key.
		 *
		 * @since 15xxxx Initial release.
		 *
		 * @param string      $function `__FUNCTION__` is suggested here.
		 *    i.e. the calling function name in the calling class.
		 *
		 * @param mixed|array $args The arguments to the calling function.
		 *    Using `func_get_args()` to the caller might suffice in some cases.
		 *    That said, it's generally a good idea to customize this a bit.
		 *    This should include the cachable arguments only.
		 *
		 * @param string      $___prop For internal use only. This defaults to `cache`.
		 *    See also: {@link static_key()} where a value of `static` is used instead.
		 *
		 * @return mixed|null Returns the current value for the cache key.
		 *    Or, this returns `NULL` if the key is not set yet.
		 *
		 * @note This function returns by reference. The use of `&` is highly recommended when calling this utility.
		 *    See also: <http://php.net/manual/en/language.references.return.php>
		 */
		protected function &cacheKey($function, $args = array(), $___prop = 'cache')
		{
			$function = (string)$function;
			$args     = (array)$args;

			if(!isset($this->{$___prop}[$function]))
				$this->{$___prop}[$function] = NULL;
			$cache_key = &$this->{$___prop}[$function];

			foreach($args as $_arg) // Use each arg as a key.
			{
				switch(strtolower(gettype($_arg)))
				{
					case 'integer':
						$_key = (integer)$_arg;
						break; // Break switch handler.

					case 'double':
					case 'float':
						$_key = (string)$_arg;
						break; // Break switch handler.

					case 'boolean':
						$_key = (integer)$_arg;
						break; // Break switch handler.

					case 'array':
					case 'object':
						$_key = sha1(serialize($_arg));
						break; // Break switch handler.

					case 'null':
					case 'resource':
					case 'unknown type':
					default: // Default case handler.
						$_key = "\0".(string)$_arg;
				}
				if(!isset($cache_key[$_key]))
					$cache_key[$_key] = NULL;
				$cache_key = &$cache_key[$_key];
			}
			return $cache_key;
		}

		/**
		 * Construct and acquire a static key.
		 *
		 * @since 15xxxx Initial release.
		 *
		 * @param string      $function See {@link cache_key()}.
		 * @param mixed|array $args See {@link cache_key()}.
		 *
		 * @return mixed|null See {@link cache_key()}.
		 *
		 * @note This function returns by reference. The use of `&` is highly recommended when calling this utility.
		 *    See also: <http://php.net/manual/en/language.references.return.php>
		 */
		protected function &staticKey($function, $args = array())
		{
			$key = &$this->cacheKey($function, $args, 'static');

			return $key; // By reference.
		}

		/**
		 * Unset cache keys.
		 *
		 * @since 15xxxx Initial release.
		 *
		 * @param array $preserve Preserve certain keys?
		 */
		protected function unsetCacheKeys(array $preserve = array())
		{
			foreach($this->cache as $_key => $_value)
				if(!$preserve || !in_array($_key, $preserve, TRUE))
					unset($this->cache[$_key]);
			unset($_key, $_value); // Housekeeping.
		}

		/**
		 * Unset static keys.
		 *
		 * @since 15xxxx Initial release.
		 *
		 * @param array $preserve Preserve certain keys?
		 */
		protected function unsetStaticKeys(array $preserve = array())
		{
			foreach($this->static as $_key => $_value)
				if(!$preserve || !in_array($_key, $preserve, TRUE))
					unset($this->static[$_key]);
			unset($_key, $_value); // Housekeeping.
		}
	}
}