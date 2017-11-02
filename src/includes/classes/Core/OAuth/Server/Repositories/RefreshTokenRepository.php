<?php
/**
 * OAuth refresh token repository.
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
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
#
use WebSharks\Core\Classes\Core\OAuth\Server\Entities\ClientEntity;
use WebSharks\Core\Classes\Core\OAuth\Server\Entities\RefreshTokenEntity;
#
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;

/**
 * OAuth refresh token repository.
 *
 * @since 17xxxx
 */
abstract class RefreshTokenRepository extends Repository implements RefreshTokenRepositoryInterface
{
    /**
     * New refresh token entity.
     *
     * @return RefreshTokenEntityInterface Refresh token entity.
     */
    public function getNewRefreshToken()
    {
        return $this->App->Di->get(RefreshTokenEntity::class);
    }

    /*
     * Store refresh token in database.
     *
     * @param RefreshTokenEntityInterface $RefreshTokenEntity Refresh token entity.
     *
     * @throws UniqueTokenIdentifierConstraintViolationException::create()
     */
    # abstract public function persistNewRefreshToken(RefreshTokenEntityInterface $RefreshTokenEntity);

    /*
     * Revoke refresh token.
     *
     * @param string $identifier Identifier.
     */
    # abstract public function revokeRefreshToken($identifier);

    /*
     * Refresh token revoked?
     *
     * @param string $identifier Identifier.
     *
     * @return bool True if revoked.
     */
    # abstract public function isRefreshTokenRevoked($identifier);
}
