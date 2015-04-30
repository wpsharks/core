<?php
namespace WebSharks\Core\Traits;

/**
 * Array Dot-Key Utilities.
 *
 * @since 150424 Initial release.
 */
trait ArrayDotKeyUtils
{
    abstract protected function arrayIterator(array $array);

    /**
     * Builds an array w/ ONE dimension; using DOT `.` keys (e.g., `key.ID`).
     *
     * @param array $array Any input array will do fine here.
     *
     * @return array An array w/ ONE dimension; using DOT `.` keys.
     */
    protected function arrayDotKeys(array $array)
    {
        $iterator = $this->arrayIterator($array);

        foreach ($iterator as $_key => $_value) {
            $_keys = array(); // Initialize keys.

            foreach (range(0, $iterator->getDepth()) as $_depth) {
                $_keys[] = $iterator->getSubIterator($_depth)->key();
            }
            $dot_keys[implode('.', $_keys)] = $_value;

            unset($_keys, $_depth); // Housekeeping.
        }
        unset($_key, $_value); // Housekeeping.

        return !empty($dot_keys) ? $dot_keys : array();
    }
}
