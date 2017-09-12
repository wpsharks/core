<?php
/**
 * UTF-8 utils.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * UTF-8 utils.
 *
 * @since 150424 Enhancing multibyte support.
 */
class Utf8 extends Classes\Core\Base\Core
{
    /**
     * Marker.
     *
     * @since 150424
     *
     * @var string
     */
    const BOM = "\xEF\xBB\xBF";

    /**
     * Is valid UTF-8?
     *
     * @since 150424 Enhancing multibyte support.
     *
     * @param string $string Input string to test.
     *
     * @return bool True if is valid UTF-8.
     */
    public function isValid(string $string): bool
    {
        if (!isset($string[0])) {
            return true; // Nothing to do.
        }
        return (bool) preg_match('/^./us', $string);
    }
}
