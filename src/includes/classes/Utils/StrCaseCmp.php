<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Multibyte `strcasecmp()`.
 *
 * @since 150424 Enhancing multibyte support.
 */
class StrCaseCmp extends Classes\Core
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
