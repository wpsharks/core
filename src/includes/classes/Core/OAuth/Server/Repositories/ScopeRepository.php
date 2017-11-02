<?php
/**
 * OAuth scope repository.
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
use League\OAuth2\Server\Entities\ScopeEntityInterface;
#
use WebSharks\Core\Classes\Core\OAuth\Server\Entities\ClientEntity;
use WebSharks\Core\Classes\Core\OAuth\Server\Entities\ScopeEntity;
#
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;

/**
 * OAuth scope repository.
 *
 * @since 17xxxx
 */
abstract class ScopeRepository extends Repository implements ScopeRepositoryInterface
{
    /**
     * Hard-coded scopes array.
     *
     * @since 17xxxx OAuth scope repository.
     *
     * @type array Hard-coded scopes array.
     */
    const SCOPES = [];

    /**
     * Get scope entity.
     *
     * @since 17xxxx OAuth scope repository.
     *
     * @param string $identifier Identifier.
     *
     * @return ScopeEntity|null Scope entity.
     */
    public function getScopeEntityByIdentifier($identifier)
    {
        if (!empty($this::SCOPES[$identifier])) {
            return $this->App->Di->get(ScopeEntity::class, ['identifier' => $identifier]);
        }
    }

    /**
     * Given a client, grant type and optional user identifier,
     * validate the set of scopes requested are valid and optionally
     * append additional scopes or remove requested scopes.
     *
     * @since 17xxxx OAuth scope repository.
     *
     * @param ScopeEntityInterface[] $scopes          Scopes.
     * @param string                 $grant_type      Grant type.
     * @param ClientEntityInterface  $ClientEntity    Client entity.
     * @param string|null            $user_identifier User identifier.
     *
     * @return ScopeEntityInterface[] Scope entities.
     */
    public function finalizeScopes(array $scopes, $grant_type, ClientEntityInterface $ClientEntity, $user_identifier = null)
    {
        return $scopes; // No filters at this time.
    }
}
