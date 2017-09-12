<?php
/**
 * Stripe.
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
 * Stripe.
 *
 * @since 170329.13807
 */
trait Stripe
{
    /**
     * @since 170329.13807 Stripe utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Stripe::customer()
     */
    public static function stripeCustomer(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Stripe->customer(...$args);
    }

    /**
     * @since 170329.13807 Stripe utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Stripe::updateCustomer()
     */
    public static function stripeUpdateCustomer(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Stripe->updateCustomer(...$args);
    }

    /**
     * @since 170329.13807 Stripe utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Stripe::charge()
     */
    public static function stripeCharge(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Stripe->charge(...$args);
    }

    /**
     * @since 170329.13807 Stripe utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Stripe::plan()
     */
    public static function stripePlan(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Stripe->plan(...$args);
    }

    /**
     * @since 170329.13807 Stripe utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Stripe::subscription()
     */
    public static function stripeSubscription(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Stripe->subscription(...$args);
    }

    /**
     * @since 170329.13807 Stripe utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Stripe::jsTokenMarkup()
     */
    public static function stripeJsTokenMarkup(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Stripe->jsTokenMarkup(...$args);
    }
}
