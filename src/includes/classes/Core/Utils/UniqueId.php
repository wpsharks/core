<?php
/**
 * Unique ID generator.
 *
 * @author @jaswrks
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
#
use Ramsey\Uuid\Uuid as UuidGen;

/**
 * Unique ID generator.
 *
 * @since 160701 Initial release.
 */
class UniqueId extends Classes\Core\Base\Core
{
    /**
     * Unique ID generator.
     *
     * @since 160701 Initial release.
     *
     * @param string $prefix       An optional prefix.
     * @param bool   $more_entropy False results in a less-unique ID.
     *
     * @return string `$prefix` + 22 bytes (by default).
     *                If `$more_entropy` is false, `$prefix` + 13 bytes.
     *
     * @internal Unique identifier is based on the current time in microseconds.
     *  This ID contains alphanumeric ASCII chars (lowercase alpha only).
     *
     * @link http://php.net/manual/en/function.uniqid.php
     */
    public function __invoke(string $prefix = '', bool $more_entropy = true): string
    {
        return str_replace('.', '', uniqid($prefix, $more_entropy));
    }
}
