<?php
/**
 * String splitter.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
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
 * String splitter.
 *
 * @since 170824.30708 String splitter.
 */
class Split extends Classes\Core\Base\Core
{
    /**
     * String splitter.
     *
     * @since 170824.30708 String splitter.
     *
     * @param string $pattern Regex split pattern.
     * @param string $string  String to split (subject).
     * @param int    $limit   Limit, see {@link preg_split()}.
     * @param int    $flags   Flags, see {@link preg_split()}. Defaults to `PREG_SPLIT_NO_EMPTY`.
     *
     * @return array Split parts.
     */
    public function __invoke(string $pattern, string $string, int $limit = -1, int $flags = PREG_SPLIT_NO_EMPTY): array
    {
        $array        = preg_split($pattern, $string, $limit, $flags);
        return $array = is_array($array) ? $array : [];
    }
}
