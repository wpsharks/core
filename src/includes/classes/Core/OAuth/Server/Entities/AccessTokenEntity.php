<?php
/**
 * OAuth access token entity.
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
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
#
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;

/**
 * OAuth access token entity.
 *
 * @since 17xxxx
 */
class AccessTokenEntity extends Entity implements AccessTokenEntityInterface
{
    /**
     * Client entity.
     *
     * @since 17xxxx
     *
     * @type ClientEntity|null
     */
    protected $ClientEntity;

    /**
     * User identifier.
     *
     * @since 17xxxx
     *
     * @type string
     */
    protected $user_identifier;

    /**
     * Scopes.
     *
     * @since 17xxxx
     *
     * @type array
     */
    protected $scopes;

    /**
     * Expiry date/time.
     *
     * @since 17xxxx
     *
     * @type \DateTime|null
     */
    protected $expiryDateTime;

    /**
     * Class constructor.
     *
     * @since 17xxxx OAuth utils.
     *
     * @param Classes\App       $App             App.
     * @param string            $identifier      Identifier.
     * @param ClientEntity|null $ClientEntity    Client entity.
     * @param string|int        $user_identifier User identifier.
     * @param ScopeEntity[]     $scopes          Scope entity array.
     * @param \DateTime         $expiryDateTime  Expiry date/time.
     */
    public function __construct(
        Classes\App $App,
        string $identifier = '',
        ClientEntity $ClientEntity = null,
        string $user_identifier = '',
        array $scopes = [],
        \DateTime $expiryDateTime = null
    ) {
        parent::__construct($App, $identifier);

        $this->ClientEntity    = $ClientEntity;
        $this->user_identifier = $user_identifier;
        $this->scopes          = $scopes;
        $this->expiryDateTime  = $expiryDateTime;
    }

    /**
     * Get client entity.
     *
     * @since 17xxxx OAuth utils.
     *
     * @return ClientEntityInterface|null Client entity.
     */
    public function getClient()
    {
        return $this->ClientEntity;
    }

    /**
     * Set client entity.
     *
     * @since 17xxxx OAuth utils.
     *
     * @param ClientEntityInterface $ClientEntity Client entity.
     */
    public function setClient(ClientEntityInterface $ClientEntity)
    {
        $this->ClientEntity = $ClientEntity;
    }

    /**
     * Get user identifier.
     *
     * @since 17xxxx OAuth utils.
     *
     * @return string User identifier.
     */
    public function getUserIdentifier()
    {
        return $this->user_identifier;
    }

    /**
     * Set user identifier.
     *
     * @since 17xxxx OAuth utils.
     *
     * @param string $user_identifier User identifier.
     */
    public function setUserIdentifier($user_identifier)
    {
        $this->user_identifier = $user_identifier;
    }

    /**
     * Get scope entities.
     *
     * @since 17xxxx OAuth utils.
     *
     * @return ScopeEntityInterface[] Scopes.
     */
    public function getScopes()
    {
        return array_values($this->scopes);
    }

    /**
     * Add a scope entity.
     *
     * @since 17xxxx OAuth utils.
     *
     * @param ScopeEntityInterface $ScopeEntity Scope entity.
     */
    public function addScope(ScopeEntityInterface $ScopeEntity)
    {
        $this->scopes[$ScopeEntity->getIdentifier()] = $ScopeEntity;
    }

    /**
     * Remove a scope entity.
     *
     * @since 17xxxx OAuth utils.
     *
     * @param string $scope_identifier Scope identifier.
     */
    public function removeScope($scope_identifier)
    {
        unset($this->scopes[$scope_identifier]);
    }

    /**
     * Get expiry date/time.
     *
     * @since 17xxxx OAuth utils.
     *
     * @return \DateTime|null Expiry date/time.
     */
    public function getExpiryDateTime()
    {
        return $this->expiryDateTime;
    }

    /**
     * Set expiry date/time.
     *
     * @since 17xxxx OAuth utils.
     *
     * @param \DateTime $DateTime Expiry date/time.
     */
    public function setExpiryDateTime(\DateTime $DateTime)
    {
        $this->expiryDateTime = $DateTime;
    }

    /**
     * Convert to JWT (token).
     *
     * @param CryptKey $CryptKey Private key.
     *
     * @return string JWT (token).
     */
    public function convertToJWT(CryptKey $CryptKey)
    {
        return (new Builder())
            ->setAudience($this->getClient()->getIdentifier())
            ->setId($this->getIdentifier(), true)
            ->setIssuedAt(time())
            ->setNotBefore(time())
            ->setExpiration($this->getExpiryDateTime()->getTimestamp())
            ->setSubject($this->getUserIdentifier())
            ->set('scopes', $this->getScopes())
            ->sign(new Sha256(), new Key($CryptKey->getKeyPath(), $CryptKey->getPassPhrase()))
            ->getToken();
    }
}
