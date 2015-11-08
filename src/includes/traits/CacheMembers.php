<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits;

/**
 * Cache members.
 *
 * @since 150424 Initial release.
 */
trait CacheMembers
{
    /**
     * @type array Instance cache.
     *
     * @since 15xxxx Initial release.
     */
    protected $cache = array();

    /**
     * @type array Global static cache ref.
     *
     * @since 15xxxx Initial release.
     */
    protected $static = array();

    /**
     * @type array Global static cache.
     *
     * @since 15xxxx Initial release.
     */
    protected static $static_global = array();

    /**
     * Cache initializer.
     *
     * @since 15xxxx Initial release.
     */
    protected function cacheInit()
    {
        $class = get_called_class();

        if (empty(static::$static_global[$class])) {
            static::$static_global[$class] = array();
        }
        $this->static = &static::$static_global[$class];
    }

    /**
     * Construct and acquire a cache key.
     *
     * @since 15xxxx Initial release.
     *
     * @param string      $function `__FUNCTION__` is suggested here.
     *                              i.e. the calling function name in the calling class.
     * @param mixed|array $args     The arguments to the calling function.
     *                              Using `func_get_args()` to the caller might suffice.
     *                              That said, it's generally a good idea to customize this a bit.
     *                              This should include the cachable arguments only.
     * @param string      $___prop  For internal use only. This defaults to `cache`.
     *                              See also: {@link staticKey()} where a value of `static` is used instead.
     *
     * @return mixed|null Returns the current value for the cache key. Returns `NULL` if key is not set.
     *
     * @note This function returns by reference. The use of `&` is highly recommended when calling this utility.
     *    See also: <http://php.net/manual/en/language.references.return.php>
     */
    protected function &cacheKey(string $function, $args = [], string $___prop = 'cache')
    {
        $args = (array) $args; // Force array.

        if (!isset($this->{$___prop}[$function])) {
            $this->{$___prop}[$function] = null;
        }
        $cache_key = &$this->{$___prop}[$function];

        foreach ($args as $_arg) {
            switch (mb_strtolower(gettype($_arg))) {
                case 'int':
                case 'integer':
                    $_key = (int) $_arg;
                    break; // Break switch handler.

                case 'double':
                case 'float':
                case 'real':
                    $_key = (string) $_arg;
                    break; // Break switch handler.

                case 'bool':
                case 'boolean':
                    $_key = (int) $_arg;
                    break; // Break switch handler.

                case 'array':
                case 'object':
                    $_key = sha1(serialize($_arg));
                    break; // Break switch handler.

                case 'null':
                case 'resource':
                case 'unknown type':
                default: // Default case handler.
                    $_key = "\0".(string) $_arg;
            }
            if (!isset($cache_key[$_key])) {
                $cache_key[$_key] = null;
            }
            $cache_key = &$cache_key[$_key];
        } // unset($_arg); // Housekeeping.

        return $cache_key;
    }

    /**
     * Construct and acquire a static key.
     *
     * @since 15xxxx Initial release.
     *
     * @param string      $function See {@link cache_key()}.
     * @param mixed|array $args     See {@link cache_key()}.
     *
     * @return mixed|null See {@link cache_key()}.
     *
     * @note This function returns by reference. The use of `&` is highly recommended when calling this utility.
     *    See also: <http://php.net/manual/en/language.references.return.php>
     */
    protected function &staticKey(string $function, $args = array())
    {
        $key = &$this->cacheKey($function, $args, 'static');

        return $key; // By reference.
    }

    /**
     * Unset cache.
     *
     * @since 15xxxx Initial release.
     *
     * @param array $preserve_keys Preserve?
     */
    protected function cacheUnset(array $preserve_keys = [])
    {
        foreach ($this->cache as $_key => $_value) {
            if (!$preserve_keys || !in_array($_key, $preserve_keys, true)) {
                unset($this->cache[$_key]);
            }
        } // unset($_key, $_value); // Housekeeping.
    }

    /**
     * Unset static keys.
     *
     * @since 15xxxx Initial release.
     *
     * @param array $preserve_keys Preserve?
     */
    protected function staticUnset(array $preserve_keys = [])
    {
        foreach ($this->static as $_key => $_value) {
            if (!$preserve_keys || !in_array($_key, $preserve_keys, true)) {
                unset($this->static[$_key]);
            }
        } // unset($_key, $_value); // Housekeeping.
    }
}
