<?php
namespace WebSharks\Core\Classes\Utils;

/**
 * Array iterator utilities.
 *
 * @since 150424 Initial release.
 */
class ArrayIteratorUtils extends AbsBase
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
    public function arrayIterator(array $array)
    {
        return new \RecursiveIteratorIterator(new \RecursiveArrayIterator($array));
    }
}
