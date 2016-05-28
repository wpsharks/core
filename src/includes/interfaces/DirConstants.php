<?php
declare (strict_types = 1);
namespace WebSharks\Core\Interfaces;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Directory constants.
 *
 * @since 160528 Constants.
 */
interface DirConstants
{
    /**
     * Directory separator.
     *
     * @since 160528 Constants.
     *
     * @type string Directory separator.
     */
    const DIR_SEP = DIRECTORY_SEPARATOR;
}
