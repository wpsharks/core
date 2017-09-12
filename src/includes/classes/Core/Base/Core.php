<?php
/**
 * Core abstraction.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Base;

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
