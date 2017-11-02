<?php
/**
 * OAuth scope entity.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\OAuth\Server\Entities;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
#
use function assert as debug;
use function get_defined_vars as vars;
#
use League\OAuth2\Server\Entities\ScopeEntityInterface;

/**
 * OAuth scope entity.
 *
 * @since 17xxxx
 */
class ScopeEntity extends Entity implements ScopeEntityInterface
{
}
