<?php
declare(strict_types=1);
namespace WebSharks\Core;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$App                         = c::app();
$Config                      = $App->Config;
$Config->©stripe['©api_key'] = 'sk_test_xxxxxxxxxxxxxxxxxxxxxxxxx';

/* ------------------------------------------------------------------------------------------------------------------ */

// Test customer creation.
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

// Test customer retrieval by ID.
$Customer = c::stripeCustomer($Customer->id);
if (c::isError($Error = $Customer)) {
    throw c::issue($Customer, $Error->message());
}

/* ------------------------------------------------------------------------------------------------------------------ */

// After adding a test card manually.

// Test charging the customer.
$Charge = c::stripeCharge([
    'customer'  => 'cus_AH36AxjaOk2vvO',

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

// Test charge retrieval by ID.
$Charge = c::stripeCharge($Charge->id);
if (c::isError($Error = $Charge)) {
    throw c::issue($Charge, $Error->message());
}

/* ------------------------------------------------------------------------------------------------------------------ */

// Test plan creation.
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

// Test plan retrieval by ID.
$Plan = c::stripePlan($Plan->id);
if (c::isError($Error = $Plan)) {
    throw c::issue($Plan, $Error->message());
}

/* ------------------------------------------------------------------------------------------------------------------ */

// Test subscription creation.
$Subscription = c::stripeSubscription([
    'customer'  => 'cus_AH36AxjaOk2vvO',
    'plan'      => $Plan->id,

    'order_id'  => 1,
    'metadata'  => [
        'hello' => 'world',
    ],
]);
if (c::isError($Error = $Subscription)) {
    throw c::issue($Subscription, $Error->message());
}

// Test subscription retrieval by ID.
$Subscription = c::stripeSubscription($Subscription->id);
if (c::isError($Error = $Subscription)) {
    throw c::issue($Subscription, $Error->message());
}
