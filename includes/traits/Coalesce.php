<?php
/**
 * Coalesce
 *
 * @since 15xxxx Initial release.
 * @copyright WebSharks, Inc. <http://www.websharks-inc.com>
 * @license GNU General Public License, version 3
 */
namespace WebSharks\Core\Traits
{
	/**
	 * Coalesce
	 *
	 * @since 15xxxx Initial release.
	 */
	trait Coalesce
	{
		/**
		 * Utility; `!empty()` coalesce.
		 *
		 * @since 15xxxx Initial release.
		 *
		 * @return mixed First `!empty()`; else `NULL`.
		 */
		protected function coalesce()
		{
			foreach(func_get_args() as $var)
				if(!empty($var)) // Not empty?
					return $var;

			return NULL; // Default value.
		}
	}
}