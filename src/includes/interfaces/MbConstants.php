<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits;

/**
 * Multibyte-related constants.
 *
 * @since 150424 Initial release.
 */
interface MbConstants
{
    /**
     * Multibyte detection order.
     *
     * @since 150424 Initial release.
     *
     * @type array Default character encoding detections.
     */
    const MB_DETECTION_ORDER = ['UTF-8', 'ISO-8859-1'];
}
