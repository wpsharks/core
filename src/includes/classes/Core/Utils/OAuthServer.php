<?php
/**
 * OAuth server utils.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

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
use Slim\Http\Body;
#
use WebSharks\Core\Classes\Core\Request;
use WebSharks\Core\Classes\Core\Response;
#
use League\OAuth2\Server\ResourceServer;
use League\OAuth2\Server\AuthorizationServer;
#
use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\ImplicitGrant;
#
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\RequestTypes\AuthorizationRequest;
#
use WebSharks\Core\Classes\Core\OAuth\Server\Entities\AccessTokenEntity;
use WebSharks\Core\Classes\Core\OAuth\Server\Entities\AuthCodeEntity;
use WebSharks\Core\Classes\Core\OAuth\Server\Entities\ClientEntity;
use WebSharks\Core\Classes\Core\OAuth\Server\Entities\RefreshTokenEntity;
use WebSharks\Core\Classes\Core\OAuth\Server\Entities\ScopeEntity;
use WebSharks\Core\Classes\Core\OAuth\Server\Entities\UserEntity;
#
use WebSharks\Core\Classes\Core\OAuth\Server\Repositories\AccessTokenRepository;
use WebSharks\Core\Classes\Core\OAuth\Server\Repositories\AuthCodeRepository;
use WebSharks\Core\Classes\Core\OAuth\Server\Repositories\ClientRepository;
use WebSharks\Core\Classes\Core\OAuth\Server\Repositories\RefreshTokenRepository;
use WebSharks\Core\Classes\Core\OAuth\Server\Repositories\ScopeRepository;
use WebSharks\Core\Classes\Core\OAuth\Server\Repositories\UserRepository;

/**
 * OAuth server utils.
 *
 * @since 17xxxx
 */
class OAuthServer extends Classes\Core\Base\Core
{
    /**
     * Authorization server.
     *
     * @since 17xxxx
     *
     * @type AuthorizationServer|null
     */
    protected $AuthorizationServer;

    /**
     * Resource server.
     *
     * @since 17xxxx
     *
     * @type ResourceServer|null
     */
    protected $ResourceServer;

    /**
     * Access token repo.
     *
     * @since 17xxxx
     *
     * @type AccessTokenRepository|null
     */
    protected $AccessTokenRepository;

    /**
     * Authorize.
     *
     * @since 17xxxx OAuth utils.
     *
     * @param Request  $Request  Request.
     * @param Response $Response Response.
     * @param callable $callback Callback.
     */
    public function authorize(Request $Request, Response $Response, callable $callback)
    {
        try { // Handles `/authorize` endpoint.
            if (!$this->c::isActiveSession()) {
                throw $this->c::issue('Requires active session.');
            }
            $_SESSION['oauth']   = $_SESSION['oauth'] ?? [];
            $AuthorizationServer = $this->authorizationServer();

            $_nonce_action       = 'oauth-authorize';
            $_nonce              = (string) $Request->getParam('_nonce');

            if ($_nonce && !$this->c::verifyNonce($_nonce_action, $_nonce)) {
                throw $this->c::issue('Unable to verify `_nonce`.');
            }
            if ($_nonce && !empty($_SESSION['oauth'][$_nonce]['AuthorizationRequest'])) {
                if (!($AuthorizationRequest = unserialize($_SESSION['oauth'][$_nonce]['AuthorizationRequest']))) {
                    throw $this->c::issue('Unable to unserialize `AuthorizationRequest`.');
                }
                $user_id  = (string) ($_SESSION['oauth']['user_id'] ?? '');
                $approved = $this->c::isTruthy($Request->getParam('approved'));

                if ($user_id && $approved) {
                    $UserEntity = $this->App->Di->get(UserEntity::class);
                    $UserEntity->setIdentifier($user_id);

                    $AuthorizationRequest->setUser($UserEntity);
                    $AuthorizationRequest->setAuthorizationApproved(true);

                    unset($_SESSION['oauth']);
                    $Response = $AuthorizationServer->completeAuthorizationRequest($AuthorizationRequest, $Response);
                    $Response->output(['exit' => true]);
                } else {
                    $_nonce                                             = $this->c::createNonce($_nonce_action);
                    $_SESSION['oauth'][$_nonce]['AuthorizationRequest'] = serialize($AuthorizationRequest);

                    $callback($AuthorizationRequest, $_nonce);
                    exit; // Stop here.
                }
            } else {
                $AuthorizationRequest                               = $AuthorizationServer->validateAuthorizationRequest($Request);
                $_nonce                                             = $this->c::createNonce($_nonce_action);
                $_SESSION['oauth'][$_nonce]['AuthorizationRequest'] = serialize($AuthorizationRequest);

                $callback($AuthorizationRequest, $_nonce);
                exit; // Stop here.
            }
        } catch (OAuthServerException $Exception) {
            unset($_SESSION['oauth']);
            $Response = $Exception->generateHttpResponse($Response);
            $Response->output(['exit' => true]);
            //
        } catch (\Throwable $Exception) {
            unset($_SESSION['oauth']);
            $Exception = new OAuthServerException($Exception->getMessage(), 0, 'unknown', 500);
            $Response  = $Exception->generateHttpResponse($Response);
            $Response->output(['exit' => true]);
        }
    }

    /**
     * Issue token.
     *
     * @since 17xxxx OAuth utils.
     *
     * @param Request  $Request  Request.
     * @param Response $Response Response.
     */
    public function issueToken(Request $Request, Response $Response)
    {
        try { // Handles `/(access|refresh)-token` endpoints.
            $AuthorizationServer = $this->authorizationServer();
            $Response            = $AuthorizationServer->respondToAccessTokenRequest($Request, $Response);
            $Response->output(['exit' => true]);
            //
        } catch (OAuthServerException $Exception) {
            $Response = $Exception->generateHttpResponse($Response);
            $Response->output(['exit' => true]);
            //
        } catch (\Throwable $Exception) {
            $Exception = new OAuthServerException($Exception->getMessage(), 0, 'unknown', 500);
            $Response  = $Exception->generateHttpResponse($Response);
            $Response->output(['exit' => true]);
        }
    }

    /**
     * Filter resource request.
     *
     * @since 17xxxx OAuth utils.
     *
     * @param Request  $Request  Request.
     * @param Response $Response Response.
     *
     * @return Request w/ `oauth_` attributes.
     *
     * If access token is valid the following attributes are set:
     * - `oauth_access_token_id` the access token identifier.
     * - `oauth_client_id` the client identifier.
     * - `oauth_user_id` the user identifier represented by the access token.
     * - `oauth_scopes` an array of string scope identifiers.
     */
    public function resourceRequest(Request $Request, Response $Response): Request
    {
        try { // Authenticate resource request.
            $ResourceServer = $this->resourceServer();
            return $Request = $ResourceServer->validateAuthenticatedRequest($Request);
            //
        } catch (OAuthServerException $Exception) {
            $Response = $Exception->generateHttpResponse($Response);
            $Response->output(['exit' => true]);
            //
        } catch (\Throwable $Exception) {
            $Exception = new OAuthServerException($Exception->getMessage(), 0, 'unknown', 500);
            $Response  = $Exception->generateHttpResponse($Response);
            $Response->output(['exit' => true]);
        }
    }

    /**
     * Access token repository.
     *
     * @since 17xxxx OAuth utils.
     *
     * @return AccessTokenRepository Repo.
     */
    protected function accessTokenRepository(): AccessTokenRepository
    {
        if ($this->AccessTokenRepository) {
            return $this->AccessTokenRepository;
        }
        return $this->AccessTokenRepository = $this->App->Di->get(AccessTokenRepository::class);
    }

    /**
     * Get resource server.
     *
     * @since 17xxxx OAuth utils.
     *
     * @return ResourceServer Server.
     */
    protected function resourceServer(): ResourceServer
    {
        if ($this->ResourceServer) {
            return $this->ResourceServer;
        }
        return $this->ResourceServer = new ResourceServer($this->accessTokenRepository(), $this->App->Config->©oauth['©public_key']);
    }

    /**
     * Get authorization server.
     *
     * @since 17xxxx OAuth utils.
     *
     * @return AuthorizationServer Server.
     */
    protected function authorizationServer(): AuthorizationServer
    {
        if ($this->AuthorizationServer) {
            return $this->AuthorizationServer;
        }
        $_one_month  = new \DateInterval('P1M');
        $_one_day    = new \DateInterval('P1D');
        $_10_minutes = new \DateInterval('PT10M');

        $AuthCodeRepository     = $this->App->Di->get(AuthCodeRepository::class);
        $ClientRepository       = $this->App->Di->get(ClientRepository::class);
        $RefreshTokenRepository = $this->App->Di->get(RefreshTokenRepository::class);
        $ScopeRepository        = $this->App->Di->get(ScopeRepository::class);
        $UserRepository         = $this->App->Di->get(UserRepository::class);

        $this->AuthorizationServer = new AuthorizationServer(
            $ClientRepository,
            $this->accessTokenRepository(),
            $ScopeRepository,
            $this->App->Config->©oauth['©private_key'],
            $this->App->Config->©oauth['©encryption_key']
        );
        $AuthCodeGrant          = new AuthCodeGrant($AuthCodeRepository, $RefreshTokenRepository, $_10_minutes);
        $PasswordGrant          = new PasswordGrant($UserRepository, $RefreshTokenRepository);
        $RefreshTokenGrant      = new RefreshTokenGrant($RefreshTokenRepository);
        $ClientCredentialsGrant = new ClientCredentialsGrant();
        $ImplicitGrant          = new ImplicitGrant($_one_day);

        $AuthCodeGrant->setRefreshTokenTTL($_one_month);
        $PasswordGrant->setRefreshTokenTTL($_one_month);
        $RefreshTokenGrant->setRefreshTokenTTL($_one_month);

        $this->AuthorizationServer->enableGrantType($AuthCodeGrant, $_one_day);
        $this->AuthorizationServer->enableGrantType($PasswordGrant, $_one_day);
        $this->AuthorizationServer->enableGrantType($RefreshTokenGrant, $_one_day);
        $this->AuthorizationServer->enableGrantType($ClientCredentialsGrant, $_one_day);
        $this->AuthorizationServer->enableGrantType($ImplicitGrant, $_one_day);

        return $this->AuthorizationServer;
    }
}
