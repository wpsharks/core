<?php
/**
 * OAuth client repository.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\OAuth\Server\Repositories;

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
use League\OAuth2\Server\Entities\ClientEntityInterface;
use WebSharks\Core\Classes\Core\OAuth\Server\Entities\ClientEntity;
#
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;

/**
 * OAuth client repository.
 *
 * @since 17xxxx
 */
abstract class ClientRepository extends Repository implements ClientRepositoryInterface
{
    /*
     * Get client entity.
     *
     * @param string      $identifier        Identifier.
     * @param string      $grant_type        Grant type.
     * @param string|null $key               Secret key.
     * @param bool        $must_validate_key Validate key?
     *
     * @return ClientEntityInterface|null Client entity.
     */
    # abstract public function getClientEntity($identifier, $grant_type, $key = null, $must_validate_key = true);
}
