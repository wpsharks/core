<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * Array iterator utilities.
 *
 * @since 150424 Initial release.
 */
class ArrayIterators extends AbsBase
{
    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Recursive iterator.
     *
     * @param array $array Any input array will do fine here.
     *
     * @return \RecursiveIteratorIterator|\RecursiveArrayIterator Recursive iterator.
     */
    public function recursive(array $array): \RecursiveIteratorIterator
    {
        return new \RecursiveIteratorIterator(new \RecursiveArrayIterator($array));
    }
}
