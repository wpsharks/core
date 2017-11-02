<?php
/**
 * OAuth auth code repository.
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
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
#
use WebSharks\Core\Classes\Core\OAuth\Server\Entities\ClientEntity;
use WebSharks\Core\Classes\Core\OAuth\Server\Entities\AuthCodeEntity;
#
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException;

/**
 * OAuth auth code repository.
 *
 * @since 17xxxx
 */
abstract class AuthCodeRepository extends Repository implements AuthCodeRepositoryInterface
{
    /**
     * New auth code entity.
     *
     * @return AuthCodeEntityInterface Auth code entity.
     */
    public function getNewAuthCode()
    {
        return $this->App->Di->get(AuthCodeEntity::class);
    }

    /*
     * Store auth code entity in database.
     *
     * @param AuthCodeEntityInterface $AuthCodeEntity Refresh token entity.
     *
     * @throws UniqueTokenIdentifierConstraintViolationException::create()
     */
    # abstract public function persistNewAuthCode(AuthCodeEntityInterface $AuthCodeEntity);

    /*
     * Revoke auto code.
     *
     * @param string $identifier Identifier.
     */
    # abstract public function revokeAuthCode($identifier);

    /*
     * Auto code revoked?
     *
     * @param string $identifier Identifier.
     *
     * @return bool True if revoked.
     */
    # abstract public function isAuthCodeRevoked($identifier);
}
