<?php
/**
 * OAuth auth code entity.
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
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\AuthCodeEntityInterface;

/**
 * OAuth auth code entity.
 *
 * @since 17xxxx
 */
class AuthCodeEntity extends Entity implements AuthCodeEntityInterface
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
     * Redirect URI.
     *
     * @since 17xxxx
     *
     * @type string
     */
    protected $redirect_uri;

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
     * @param string            $redirect_uri    Redirect URI.
     * @param ScopeEntity[]     $scopes          Scope entity array.
     * @param \DateTime         $expiryDateTime  Expiry date/time.
     */
    public function __construct(
        Classes\App $App,
        string $identifier = '',
        ClientEntity $ClientEntity = null,
        string $user_identifier = '',
        string $redirect_uri = '',
        array $scopes = [],
        \DateTime $expiryDateTime = null
    ) {
        parent::__construct($App, $identifier);

        $this->ClientEntity    = $ClientEntity;
        $this->user_identifier = $user_identifier;
        $this->redirect_uri    = $redirect_uri;
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
     * Get redirect URI.
     *
     * @since 17xxxx OAuth utils.
     *
     * @return string Redirect URI.
     */
    public function getRedirectUri()
    {
        return $this->redirect_uri;
    }

    /**
     * Set redirect URI.
     *
     * @since 17xxxx OAuth utils.
     *
     * @param string $redirect_uri Redirect URI.
     */
    public function setRedirectUri($redirect_uri)
    {
        $this->redirect_uri = $redirect_uri;
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
}
