<?php
/**
 * OAuth server utils.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Traits\Facades;

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
 * OAuth server utils.
 *
 * @since 17xxxx OAuth utils.
 */
trait OAuthServer
{
    /**
     * @since 17xxxx OAuth utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\OAuthServer::authorize()
     */
    public static function oauthAuthorize(...$args)
    {
        return $GLOBALS[static::class]->Utils->©OAuthServer->authorize(...$args);
    }

    /**
     * @since 17xxxx OAuth utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\OAuthServer::issueToken()
     */
    public static function oauthIssueToken(...$args)
    {
        return $GLOBALS[static::class]->Utils->©OAuthServer->issueToken(...$args);
    }

    /**
     * @since 17xxxx OAuth utils.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\OAuthServer::resourceRequest()
     */
    public static function oauthResourceRequest(...$args)
    {
        return $GLOBALS[static::class]->Utils->©OAuthServer->resourceRequest(...$args);
    }
}
