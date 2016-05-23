<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Array dot-key utilities.
 *
 * @since 150424 Initial release.
 */
class DotKeys extends Classes\Core\Base\Core
{
    /**
     * `.` keys (e.g., `key.ID`).
     *
     * @since 150424 Array utils.
     *
     * @param array $array Input array.
     *
     * @return array Output array.
     */
    public function __invoke(array $array): array
    {
        $dot_keys = []; // Initialize.
        $Iterator = $this->c::arrayRecursiveIterator($array);

        foreach ($Iterator as $_key => $_value) {
            $_keys = []; // Initialize keys.

            foreach (range(0, $Iterator->getDepth()) as $_depth) {
                $_keys[] = $Iterator->getSubIterator($_depth)->key();
            }
            $dot_keys[implode('.', $_keys)] = $_value;
            // unset($_keys, $_depth); // Housekeeping.
        } // unset($_key, $_value); // Housekeeping.

        return $dot_keys;
    }
}
