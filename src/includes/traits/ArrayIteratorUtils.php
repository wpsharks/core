<?php
namespace WebSharks\Core\Traits;

/**
 * Array Iterator Utilities.
 *
 * @since 150424 Initial release.
 */
trait ArrayIteratorUtils
{
    /**
     * Recursive iterator.
     *
     * @param array $array Any input array will do fine here.
     *
     * @return \RecursiveIteratorIterator|\RecursiveArrayIterator Recursive iterator.
     */
    protected function arrayIterator(array $array)
    {
        return new \RecursiveIteratorIterator(new \RecursiveArrayIterator($array));
    }
}
