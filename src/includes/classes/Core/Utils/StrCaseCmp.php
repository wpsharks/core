<?php
/**
 * Multibyte `strcasecmp()`.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
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
 * Multibyte `strcasecmp()`.
 *
 * @since 150424 Enhancing multibyte support.
 */
class StrCaseCmp extends Classes\Core\Base\Core
{
    /**
     * Multibyte `strcasecmp()`.
     *
     * @since 150424 Enhancing multibyte support.
     *
     * @param string $string1 Input string.
     * @param string $string2 Input string.
     *
     * @return int See: <http://php.net/manual/en/function.strcmp.php>
     */
    public function __invoke(string $string1, string $string2): int
    {
        return strcmp(mb_strtolower($string1), mb_strtolower($string2));
    }
}
