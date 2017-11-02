<?php
/**
 * OAuth client entity.
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

/**
 * OAuth client entity.
 *
 * @since 17xxxx
 */
class ClientEntity extends Entity implements ClientEntityInterface
{
    /**
     * Name.
     *
     * @since 17xxxx
     *
     * @type string
     */
    protected $name;

    /**
     * Redirect URI.
     *
     * @since 17xxxx
     *
     * @type string
     */
    protected $redirect_uri;

    /**
     * Class constructor.
     *
     * @since 17xxxx OAuth utils.
     *
     * @param Classes\App $App          App.
     * @param string      $identifier   Identifier.
     * @param string      $name         Client name.
     * @param string      $redirect_uri Redirect URI.
     */
    public function __construct(
        Classes\App $App,
        string $identifier = '',
        string $name = '',
        string $redirect_uri = ''
    ) {
        parent::__construct($App, $identifier);

        $this->name         = $name;
        $this->redirect_uri = $redirect_uri;
    }

    /**
     * Get name.
     *
     * @since 17xxxx OAuth utils.
     *
     * @return string Name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @since 17xxxx OAuth utils.
     *
     * @param string $name Name.
     */
    public function setName($name)
    {
        $this->name = $name;
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
}
