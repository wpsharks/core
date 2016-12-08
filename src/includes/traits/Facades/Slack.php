<?php
/**
 * Slack.
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
 * Slack.
 *
 * @since 161009
 */
trait Slack
{
    /**
     * @since 161009 Slack utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Slack::notify()
     */
    public static function slackNotify(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Slack->notify(...$args);
    }

    /**
     * @since 16xxxx Slack utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Slack::mrkdwn()
     */
    public static function slackMrkdwn(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Slack->mrkdwn(...$args);
    }
}
