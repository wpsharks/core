<?php
/**
 * Slack.
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
     * @since 170124.74961 Slack utilities.
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
