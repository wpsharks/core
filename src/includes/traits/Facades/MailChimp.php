<?php
/**
 * MailChimp.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * MailChimp.
 *
 * @since 160620
 */
trait MailChimp
{
    /**
     * @since 160620 Adding MailChimp.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\MailChimp::subscribe()
     */
    public static function mailchimpSubscribe(...$args)
    {
        return $GLOBALS[static::class]->Utils->©MailChimp->subscribe(...$args);
    }

    /**
     * @since 160620 Adding MailChimp.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\MailChimp::subscribeOrUpdate()
     */
    public static function mailchimpSubscribeOrUpdate(...$args)
    {
        return $GLOBALS[static::class]->Utils->©MailChimp->subscribeOrUpdate(...$args);
    }

    /**
     * @since 160620 Adding MailChimp.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\MailChimp::updateSubscriber()
     */
    public static function mailchimpUpdateSubscriber(...$args)
    {
        return $GLOBALS[static::class]->Utils->©MailChimp->updateSubscriber(...$args);
    }

    /**
     * @since 160620 Adding MailChimp.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\MailChimp::subscriber()
     */
    public static function mailchimpSubscriber(...$args)
    {
        return $GLOBALS[static::class]->Utils->©MailChimp->subscriber(...$args);
    }
}
