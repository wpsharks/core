<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

use WebSharks\Core\Classes\Utils;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

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
