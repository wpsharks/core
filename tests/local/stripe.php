<?php
declare(strict_types=1);
namespace WebSharks\Core;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$App                            = c::app();
$Config                         = $App->Config;
$Config->©stripe['©secret_key'] = '';

$existing_customer_id     = 'cus_AN770dvG5I94gK';
$existing_customer_source = 'card_1A2MucGVi5cyjiDwJJC6Q363';

/* ------------------------------------------------------------------------------------------------------------------ */

$Customer = c::stripeCustomer([
    'ip'        => 'xxx.xxx.xxx.xxx',
    'email'     => 'billy@example.com',
    'fname'     => 'Billy',
    'lname'     => 'Bob',

    'user_id'   => 1,
    'metadata'  => [
        'hello' => 'world',
    ],
]);
if (c::isError($Error = $Customer)) {
    throw c::issue($Customer, $Error->message());
}
$Customer = c::stripeCustomer($Customer->id);
if (c::isError($Error = $Customer)) {
    throw c::issue($Customer, $Error->message());
}

/* ------------------------------------------------------------------------------------------------------------------ */

if ($existing_customer_id && $existing_customer_source) {
    $Customer = c::stripeUpdateCustomer([
        'customer'  => $existing_customer_id,

        'ip'        => '~xxx.xxx.xxx.xxx',
        'email'     => '~billy@example.com',
        'fname'     => '~Billy',
        'lname'     => '~Bob',

        'user_id'   => 1,
        'metadata'  => [
            'hello' => '~world',
            'hi'    => '~there',
        ],
    ]);
    if (c::isError($Error = $Customer)) {
        throw c::issue($Customer, $Error->message());
    }
}

/* ------------------------------------------------------------------------------------------------------------------ */

if ($existing_customer_id && $existing_customer_source) {
    $Charge = c::stripeCharge([
        'customer'  => $existing_customer_id,

        'amount'    => 1.25,
        'currency'  => 'USD',

        'order_id'  => 1,
        'metadata'  => [
            'hello' => 'world',
        ],
    ]);
    if (c::isError($Error = $Charge)) {
        throw c::issue($Charge, $Error->message());
    }
    $Charge = c::stripeCharge($Charge->id);
    if (c::isError($Error = $Charge)) {
        throw c::issue($Charge, $Error->message());
    }
}

/* ------------------------------------------------------------------------------------------------------------------ */

$Plan = c::stripePlan([
    'name' => 'Monthly Plan',

    'trial_period_days' => 0,
    'interval_count'    => 1,
    'interval'          => 'month',

    'amount'            => 2.35,
    'currency'          => 'USD',
    'metadata'          => [
        'hello' => 'world',
    ],
]);
if (c::isError($Error = $Plan)) {
    throw c::issue($Plan, $Error->message());
}
$Plan = c::stripePlan($Plan->id);
if (c::isError($Error = $Plan)) {
    throw c::issue($Plan, $Error->message());
}

/* ------------------------------------------------------------------------------------------------------------------ */

if ($existing_customer_id && $existing_customer_source) {
    $Subscription = c::stripeSubscription([
        'customer'  => $existing_customer_id,
        'plan'      => $Plan->id,

        'order_id'  => 1,
        'metadata'  => [
            'hello' => 'world',
        ],
    ]);
    if (c::isError($Error = $Subscription)) {
        throw c::issue($Subscription, $Error->message());
    }
    $Subscription = c::stripeSubscription($Subscription->id);
    if (c::isError($Error = $Subscription)) {
        throw c::issue($Subscription, $Error->message());
    }
}
