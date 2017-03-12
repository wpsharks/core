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
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * Stripe.
 *
 * @since 17xxxx
 */
trait Stripe
{
    /**
     * @since 17xxxx Stripe utilities.
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
     * @since 17xxxx Stripe utilities.
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
     * @since 17xxxx Stripe utilities.
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
     * @since 17xxxx Stripe utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Stripe::plan()
     */
    public static function stripePlan(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Stripe->plan(...$args);
    }
}
