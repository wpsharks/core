<?php
declare (strict_types = 1);
namespace WebSharks\Core\Interfaces;

/**
 * Byte constants.
 *
 * @since 160522 Byte constants.
 */
interface ByteConstants
{
    /**
     * Bytes in one kilobyte.
     *
     * @since 160522 Byte constants.
     *
     * @type int Bytes in one kilobyte.
     */
    const BYTES_IN_KB = 1024;

    /**
     * Bytes in one megabyte.
     *
     * @since 160522 Byte constants.
     *
     * @type int Bytes in one megabyte.
     */
    const BYTES_IN_MB = 1048576;

    /**
     * Bytes in one gigabyte.
     *
     * @since 160522 Byte constants.
     *
     * @type int Bytes in one gigabyte.
     */
    const BYTES_IN_GB = 1073741824;

    /**
     * Bytes in one terabyte.
     *
     * @since 160522 Byte constants.
     *
     * @type int Bytes in one terabyte.
     */
    const BYTES_IN_TB = 1099511627776;
}
