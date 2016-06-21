<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

trait MailChimp
{
    /**
     * @since 160620 Adding MailChimp.
     */
    public static function mailchimpSubscribe(...$args)
    {
        return $GLOBALS[static::class]->Utils->©MailChimp->subscribe(...$args);
    }

    /**
     * @since 160620 Adding MailChimp.
     */
    public static function mailchimpSubscriber(...$args)
    {
        return $GLOBALS[static::class]->Utils->©MailChimp->subscriber(...$args);
    }
}
