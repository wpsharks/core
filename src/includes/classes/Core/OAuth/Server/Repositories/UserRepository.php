<?php
/**
 * OAuth user repository.
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
use League\OAuth2\Server\Entities\UserEntityInterface;
#
use WebSharks\Core\Classes\Core\OAuth\Server\Entities\ClientEntity;
use WebSharks\Core\Classes\Core\OAuth\Server\Entities\UserEntity;
#
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

/**
 * OAuth user repository.
 *
 * @since 17xxxx
 */
abstract class UserRepository extends Repository implements UserRepositoryInterface
{
    /*
     * Get user entity.
     *
     * @param string                $username     Username.
     * @param string                $password     Password.
     * @param string                $grant_type   Grant type.
     * @param ClientEntityInterface $ClientEntity Client entity.
     *
     * @return UserEntityInterface|null User entity.
     */
    # abstract public function getUserEntityByUserCredentials($username, $password, $grant_type, ClientEntityInterface $ClientEntity);
}
