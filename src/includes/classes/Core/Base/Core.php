<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Base;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function get_defined_vars as vars;

/**
 * Core abstraction.
 *
 * @since 150424 Initial release.
 */
abstract class Core implements \Serializable, \JsonSerializable
{
    use Traits\CoreConstructor;
    use Traits\CacheMembers;
    use Traits\OverloadMembers;
}
