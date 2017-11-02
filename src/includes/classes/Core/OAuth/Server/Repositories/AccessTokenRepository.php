<?php
/**
 * OAuth access token repository.
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
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
#
use WebSharks\Core\Classes\Core\OAuth\Server\Entities\ClientEntity;
use WebSharks\Core\Classes\Core\OAuth\Server\Entities\AccessTokenEntity;
#
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;

/**
 * OAuth access token repository.
 *
 * @since 17xxxx
 */
abstract class AccessTokenRepository extends Repository implements AccessTokenRepositoryInterface
{
    /**
     * New access token entity.
     *
     * @param ClientEntityInterface $ClientEntity    Client entity.
     * @param array                 $scopes          Scope entities.
     * @param mixed                 $user_identifier User identifier.
     *
     * @return AccessTokenEntity Access token entity.
     */
    public function getNewToken(ClientEntityInterface $ClientEntity, array $scopes, $user_identifier = null)
    {
        return $this->App->Di->get(AccessTokenEntity::class);
    }

    /*
     * Store access token entity in database.
     *
     * @param AccessTokenEntityInterface $AccessTokenEntity Access token entity.
     *
     * @throws UniqueTokenIdentifierConstraintViolationException::create()
     */
    # abstract public function persistNewAccessToken(AccessTokenEntityInterface $AccessTokenEntity);

    /*
     * Revoke access token.
     *
     * @param string $identifier Identifier.
     */
    # abstract public function revokeAccessToken($identifier);

    /*
     * Access token revoked?
     *
     * @param string $identifier Identifier.
     *
     * @return bool True if revoked.
     */
    # abstract public function isAccessTokenRevoked($identifier);
}
