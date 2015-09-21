<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits;

/**
 * Definition properties.
 *
 * @since 150424 Initial release.
 */
trait MbDefinitions
{
    /**
     * Multibyte detection order.
     *
     * @since 150424 Initial release.
     *
     * @type array Default character encoding detections.
     */
    protected $DEF_MB_DETECTION_ORDER = ['UTF-8', 'ISO-8859-1'];
}
