<?php
/**
 * OAuth refresh token entity.
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
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;

/**
 * OAuth refresh token entity.
 *
 * @since 17xxxx
 */
class RefreshTokenEntity extends Entity implements RefreshTokenEntityInterface
{
    /**
     * Access token entity.
     *
     * @since 17xxxx
     *
     * @type AccessTokenEntity|null
     */
    protected $AccessTokenEntity;

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
     * @param Classes\App       $App               App.
     * @param string            $identifier        Identifier.
     * @param AccessTokenEntity $AccessTokenEntity Access token.
     * @param \DateTime         $expiryDateTime    Expiry date/time.
     */
    public function __construct(
        Classes\App $App,
        string $identifier = '',
        AccessTokenEntity $AccessTokenEntity = null,
        \DateTime $expiryDateTime = null
    ) {
        parent::__construct($App, $identifier);

        $this->AccessTokenEntity = $AccessTokenEntity;
        $this->expiryDateTime    = $expiryDateTime;
    }

    /**
     * Get access token entity.
     *
     * @since 17xxxx OAuth utils.
     *
     * @return AccessTokenEntityInterface|null Access token.
     */
    public function getAccessToken()
    {
        return $this->AccessTokenEntity;
    }

    /**
     * Set access token entity.
     *
     * @since 17xxxx OAuth utils.
     *
     * @param AccessTokenEntityInterface $AccessTokenEntity Access token.
     */
    public function setAccessToken(AccessTokenEntityInterface $AccessTokenEntity)
    {
        $this->AccessTokenEntity = $AccessTokenEntity;
    }

    /**
     * Expiry date/time.
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
