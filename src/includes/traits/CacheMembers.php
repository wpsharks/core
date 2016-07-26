<?php
/**
 * Cache members.
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
 * Cache members.
 *
 * @since 150424 Initial release.
 */
trait CacheMembers
{
    /**
     * Instance cache.
     *
     * @since 150424 Initial release.
     *
     * @var array Instance cache.
     */
    protected $x___cache = [];

    /**
     * Set a cache sub-key.
     *
     * @since 150424 Initial release.
     *
     * @param string     $primary_key Primary key.
     * @param string|int $sub_key     Sub-key to set.
     * @param mixed      $value       New cache key value.
     *
     * @return mixed|null Value (by reference) for the cache sub-key.
     *
     * @internal Returns by reference. The use of `&` is highly recommended when calling this utility.
     *    See also: <http://php.net/manual/en/language.references.return.php>
     */
    protected function &cacheSet(string $primary_key, $sub_key, $value)
    {
        if ($primary_key === 'x___keys' || $primary_key === 'x___refs') {
            throw $this->c::issue('Attempting to set a reserved primary key.');
        }
        $sub_key                               = (string) $sub_key;
        $this->x___cache[$primary_key][$sub_key] = $value;
        return $this->x___cache[$primary_key][$sub_key];
    }

    /**
     * Get a cache sub-key.
     *
     * @since 150424 Initial release.
     *
     * @param string     $primary_key Primary key.
     * @param string|int $sub_key     Sub-key to get.
     *
     * @return mixed|null Value (by reference) for the cache sub-key.
     *
     * @internal Returns by reference. The use of `&` is suggested when calling this utility.
     *    See also: <http://php.net/manual/en/language.references.return.php>
     */
    protected function &cacheGet(string $primary_key, $sub_key)
    {
        if ($primary_key === 'x___keys' || $primary_key === 'x___refs') {
            throw $this->c::issue('Attempting to get a reserved primary key.');
        }
        $sub_key = (string) $sub_key; // Force string.

        if (isset($this->x___cache[$primary_key][$sub_key])) {
            return $this->x___cache[$primary_key][$sub_key];
        } else {
            $this->x___cache[$primary_key][$sub_key] = null;
            return $this->x___cache[$primary_key][$sub_key];
        }
    }

    /**
     * Unset a cache sub-key.
     *
     * @since 150424 Initial release.
     *
     * @param string     $primary_key Primary key.
     * @param string|int $sub_key     Sub-key to unset.
     */
    protected function cacheUnset(string $primary_key, $sub_key)
    {
        if ($primary_key === 'x___keys' || $primary_key === 'x___refs') {
            throw $this->c::issue('Attempting to unset a reserved primary key.');
        }
        $sub_key                               = (string) $sub_key;
        $this->x___cache[$primary_key][$sub_key] = null;
        unset($this->x___cache[$primary_key][$sub_key]);
    }

    /**
     * Unset a cache sub-key pattern.
     *
     * @since 150424 Initial release.
     *
     * @param string $primary_key     Primary key.
     * @param string $sub_key_pattern Sub-key pattern (watered-down regex).
     */
    protected function cacheUnsetPattern(string $primary_key, string $sub_key_pattern)
    {
        if ($primary_key === 'x___keys' || $primary_key === 'x___refs') {
            throw $this->c::issue('Attempting to unset a reserved primary key.');
        }
        if (empty($this->x___cache[$primary_key])) {
            return; // Nothing to do here.
        }
        $sub_key_regex = // Allow `**` to indicate everything quickly.
            $sub_key_pattern === '**' ? '' : $this->c::wRegx($sub_key_pattern, '/', true);

        if (!$sub_key_regex && $sub_key_pattern !== '**') {
            return; // Nothing to do; i.e., no regex.
        }
        foreach (array_keys($this->x___cache[$primary_key]) as $_sub_key) {
            if ($sub_key_pattern === '**' || preg_match($sub_key_regex.'i', (string) $_sub_key)) {
                $this->x___cache[$primary_key][$_sub_key] = null;
                unset($this->x___cache[$primary_key][$_sub_key]);
            }
        } // unset($_sub_key); // Housekeeping.
    }

    /**
     * Add a new cache ref-key.
     *
     * @since 150424 Initial release.
     *
     * @param string     $primary_key Primary key.
     * @param string|int $ref_key     Ref-key to set.
     * @param mixed      $value       New value (by reference).
     */
    protected function cacheAddByRef(string $primary_key, $ref_key, &$value)
    {
        $ref_key                                           = (string) $ref_key;
        $this->x___cache['x___refs'][$primary_key][$ref_key][] = &$value;
    }

    /**
     * Unset a cache ref-key.
     *
     * @since 150424 Initial release.
     *
     * @param string     $primary_key Primary key.
     * @param string|int $ref_key     Ref-key to unset.
     */
    protected function cacheUnsetByRef(string $primary_key, $ref_key)
    {
        $ref_key = (string) $ref_key; // Force string.

        if (empty($this->x___cache['x___refs'][$primary_key][$ref_key])) {
            return; // Nothing to do here.
        }
        foreach (array_keys($this->x___cache['x___refs'][$primary_key][$ref_key]) as $_key) {
            $this->x___cache['x___refs'][$primary_key][$ref_key][$_key] = null;
        } // unset($_key); // Housekeeping.
        unset($this->x___cache['x___refs'][$primary_key][$ref_key]);
    }

    /**
     * Unset a cache ref-key pattern.
     *
     * @since 150424 Initial release.
     *
     * @param string $primary_key     Primary key.
     * @param string $ref_key_pattern Ref-key pattern (watered-down regex).
     */
    protected function cacheUnsetByRefPattern(string $primary_key, string $ref_key_pattern)
    {
        if (empty($this->x___cache['x___refs'][$primary_key])) {
            return; // Nothing to do here.
        }
        $ref_key_regex = // Allow `**` to indicate everything quickly.
            $ref_key_pattern === '**' ? '' : $this->c::wRegx($ref_key_pattern, '/', true);

        if (!$ref_key_regex && $ref_key_pattern !== '**') {
            return; // Nothing to do; i.e., no regex.
        }
        foreach (array_keys($this->x___cache['x___refs'][$primary_key]) as $_ref_key) {
            if ($ref_key_pattern === '**' || preg_match($ref_key_regex.'i', (string) $_ref_key)) {
                foreach (array_keys($this->x___cache['x___refs'][$primary_key][$_ref_key]) as $_key) {
                    $this->x___cache['x___refs'][$primary_key][$_ref_key][$_key] = null;
                } // unset($_key); // Housekeeping.
                unset($this->x___cache['x___refs'][$primary_key][$_ref_key]);
            }
        } // unset($_ref_key); // Housekeeping.
    }

    /**
     * Cache key (or sub-key) based on args.
     *
     * @since 150424 Initial release.
     *
     * @param string      $primary_key Primary key.
     * @param mixed|array $args        Args to base key on.
     *
     * @return mixed|null Value (by reference) for the cache key. Default is `null`.
     *
     * @internal Returns by reference. The use of `&` is highly recommended when calling this utility.
     *    See also: <http://php.net/manual/en/language.references.return.php>
     */
    protected function &cacheKey(string $primary_key, $args = [])
    {
        $args = (array) $args; // Force an array of args.

        if (!isset($this->x___cache['x___keys'][$primary_key])) {
            $this->x___cache['x___keys'][$primary_key] = null;
        }
        $cache_key = &$this->x___cache['x___keys'][$primary_key];

        foreach ($args as $_arg) {
            switch (($_arg_type = gettype($_arg))) {
                case 'int':
                case 'integer':
                    $_key = (int) $_arg;
                    // Only integer key.
                    break; // Break switch handler.

                case 'double':
                case 'float':
                case 'real':
                    $_key = (string) $_arg;
                    // Only numeric string.
                    break; // Break switch handler.

                case 'bool':
                case 'boolean':
                    $_key = $_arg ? 'true' : 'false';
                    // Only string not sha1 or numeric.
                    break; // Break switch handler.

                case 'string':
                    $_key = sha1($_arg_type.$_arg);
                    break; // Break switch handler.

                case 'array':
                    $_key = sha1($_arg_type.serialize($_arg));
                    break; // Break switch handler.

                case 'object': // See: <http://php.net/manual/en/function.spl-object-hash.php>
                    $_key = spl_object_hash($_arg).sha1($_arg_type.get_class($_arg).serialize($_arg));
                    break; // Break switch handler.

                case 'null':
                case 'NULL':
                case 'resource':
                case 'unknown':
                case 'unknown type':
                    $_key = "\0".sha1($_arg_type.(string) $_arg);
                    break; // Break switch handler.

                default: // Default case handler.
                    throw $this->c::issue(sprintf('Unexpected arg type: `%1$s`.', $_arg_type));
            }
            if (!isset($cache_key[$_key])) {
                $cache_key[$_key] = null;
            }
            $cache_key = &$cache_key[$_key];
        } // unset($_arg, $_arg_type, $_key);

        return $cache_key;
    }

    /**
     * Clear cache (everything).
     *
     * @since 150424 Initial release.
     */
    protected function cacheClear()
    {
        if (!empty($this->x___cache['x___refs'])) { // Nullify underliers.
            foreach (array_keys($this->x___cache['x___refs']) as $_primary_key) {
                foreach (array_keys($this->x___cache['x___refs'][$_primary_key]) as $_ref_key) {
                    foreach (array_keys($this->x___cache['x___refs'][$_primary_key][$_ref_key]) as $_key) {
                        $this->x___cache['x___refs'][$_primary_key][$_ref_key][$_key] = null;
                    } // unset($_key); // Housekeeping.
                } // unset($_ref_key); // Housekeeping.
            } // unset($_primary_key); // Housekeeping.
        }
        $this->x___cache = []; // Clean slate.
    }
}
