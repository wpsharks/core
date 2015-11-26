<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\AppUtils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Array dot-key utilities.
 *
 * @since 150424 Initial release.
 */
class ArrayDotKeys extends Classes\AbsBase
{
    /**
     * DOT `.` keys (e.g., `key.ID`).
     *
     * @since 15xxxx Adding array utils.
     *
     * @param array $array Any input array will do fine here.
     *
     * @return array An array w/ ONE dimension; using DOT `.` keys.
     */
    public function get(array $array): array
    {
        $dot_keys = []; // Initialize.
        $Iterator = $this->Utils->ArrayIterators->recursive($array);

        foreach ($Iterator as $_key => $_value) {
            $_keys = array(); // Initialize keys.

            foreach (range(0, $Iterator->getDepth()) as $_depth) {
                $_keys[] = $Iterator->getSubIterator($_depth)->key();
            }
            $dot_keys[implode('.', $_keys)] = $_value;

            // unset($_keys, $_depth); // Housekeeping.
        } // unset($_key, $_value); // Housekeeping.

        return $dot_keys;
    }
}