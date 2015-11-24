<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Array iterator utilities.
 *
 * @since 150424 Initial release.
 */
class ArrayIterators extends Classes\AbsBase
{
    /**
     * Recursive iterator.
     *
     * @since 15xxxx Adding array utils.
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
