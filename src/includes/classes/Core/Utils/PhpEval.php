<?php
/**
 * Php eval utilities.
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
 * Php eval utilities.
 *
 * @since 150424 Initial release.
 */
class PhpEval extends Classes\Core\Base\Core
{
    /**
     * Evaluate PHP code.
     *
     * @since 160505 Initial release.
     *
     * @param string $x___string Input string.
     * @param array  $x___vars   Input variables.
     *
     * @return mixed Eval return value.
     */
    public function __invoke(string $x___string, array $x___vars = [])
    {
        extract($x___vars, EXTR_PREFIX_SAME, 'xps__'); // Becomes `xps___`.

        return eval($x___string);
    }
}
