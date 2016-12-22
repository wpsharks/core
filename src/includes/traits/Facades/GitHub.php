<?php
/**
 * GitHub.
 *
 * @author @jaswsinc
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * GitHub.
 *
 * @since 16xxxx
 */
trait GitHub
{
    /**
     * @since 16xxxx GitHub utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\GitHub::getRaw()
     */
    public static function gitHubGetRaw(...$args)
    {
        return $GLOBALS[static::class]->Utils->©GitHub->getRaw(...$args);
    }

    /**
     * @since 16xxxx GitHub utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\GitHub::getJson()
     */
    public static function gitHubGetJson(...$args)
    {
        return $GLOBALS[static::class]->Utils->©GitHub->getJson(...$args);
    }

    /**
     * @since 16xxxx GitHub utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\GitHub::submitJson()
     */
    public static function gitHubSubmitJson(...$args)
    {
        return $GLOBALS[static::class]->Utils->©GitHub->submitJson(...$args);
    }

    /**
     * @since 16xxxx GitHub utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\GitHub::postJson()
     */
    public static function gitHubPostJson(...$args)
    {
        return $GLOBALS[static::class]->Utils->©GitHub->postJson(...$args);
    }

    /**
     * @since 16xxxx GitHub utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\GitHub::patchJson()
     */
    public static function gitHubPatchJson(...$args)
    {
        return $GLOBALS[static::class]->Utils->©GitHub->patchJson(...$args);
    }

    /**
     * @since 16xxxx GitHub utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\GitHub::issueRefs()
     */
    public static function gitHubIssueRefs(...$args)
    {
        return $GLOBALS[static::class]->Utils->©GitHub->issueRefs(...$args);
    }

    /**
     * @since 16xxxx GitHub utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\GitHub::mdIssueRefs()
     */
    public static function mdGitHubIssueRefs(...$args)
    {
        return $GLOBALS[static::class]->Utils->©GitHub->mdIssueRefs(...$args);
    }
}
