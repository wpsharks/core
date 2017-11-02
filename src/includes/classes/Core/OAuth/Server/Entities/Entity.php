<?php
/**
 * OAuth entity.
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

/**
 * OAuth entity.
 *
 * @since 17xxxx
 */
abstract class Entity extends Classes\Core\Base\Core
{
    /**
     * Identifier.
     *
     * @since 17xxxx
     *
     * @type string
     */
    protected $identifier;

    /**
     * Class constructor.
     *
     * @since 17xxxx OAuth utils.
     *
     * @param Classes\App $App        App.
     * @param string      $identifier Identifier.
     */
    public function __construct(Classes\App $App, $identifier = '')
    {
        parent::__construct($App);

        $this->identifier = $identifier;
    }

    /**
     * Get identifier.
     *
     * @since 17xxxx OAuth utils.
     *
     * @return string Identifier.
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set identifier.
     *
     * @since 17xxxx OAuth utils.
     *
     * @param string $identifier Identifier.
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    }
}
