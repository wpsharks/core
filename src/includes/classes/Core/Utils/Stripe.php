<?php
/**
 * Stripe utilities.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;
#
use Stripe\Customer;
use Stripe\Charge;
use Stripe\Plan;
use Stripe\Subscription;
#
use Stripe\Error\Base as StripeError;
use Stripe\Error\Card as StripeCardError;
use Stripe\Error\RateLimit as StripeRateLimitError;
use Stripe\Error\InvalidRequest as StripeInvalidRequestError;
use Stripe\Error\Authentication as StripeAuthenticationError;
use Stripe\Error\ApiConnection as StripeApiConnectionError;
use Stripe\Error\Permission as StripePermissionError;

/**
 * Stripe utilities.
 *
 * @since 17xxxx Stripe utils.
 */
class Stripe extends Classes\Core\Base\Core
{
    /**
     * Stripe API version.
     *
     * @since 17xxxx Stripe utils.
     *
     * @type string Stripe API version.
     */
    const API_VERSION = '2017-02-14';

    /**
     * Class constructor.
     *
     * @since 17xxxx Stripe utils.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);
    }

    /**
     * Get (or create) customer.
     *
     * @since 17xxxx Stripe utils.
     *
     * @param string|array $args ID or args.
     *
     * @return Customer|Error Customer on success.
     */
    public function customer($args)
    {
        if (is_string($args)) {
            $args = ['customer' => $args];
        } // Convert customer ID into args.

        $default_args = [
            'api_key'  => '', // Stripe API key.
            'customer' => '', // Get existing ID.

            // Everything else is for a new customer.

            'ip'    => '', // New customer IP address.
            'email' => '', // New customer email address.

            'fname' => '', // New customer first name.
            'lname' => '', // New customer last name.

            'source'      => '', // New customer source.
            'description' => '', // New customer description.

            'user_id'  => '', // New customer user ID.
            'metadata' => '', // New customer metadata.
        ];
        $args = (array) $args; // Force array.
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);
        extract($this->parseArgs(__FUNCTION__, $args));

        $Customer = null; // Initialize.

        if ($args['customer']) {
            try { // Acquire customer by ID.
                $Customer = Customer::retrieve($args['customer'], $opts);
            } catch (\Throwable $Exception) {
                return $this->exceptionError($Exception);
            }
        } else { // Create a new customer.
            try {
                $new_customer_args = [
                    'email'       => $args['email'] ?: null,
                    'source'      => $args['source'] ?: null,
                    'description' => $args['description'] ?: null,

                    'metadata' => array_merge([
                        'ip'    => $args['ip'],
                        'email' => $args['email'],

                        'fname' => $args['fname'],
                        'lname' => $args['lname'],

                        'user_id' => $args['user_id'],
                    ], $args['metadata']),
                ];
                $new_customer_args = $this->c::removeNulls($new_customer_args);
                $Customer          = Customer::create($new_customer_args, $opts);
            } catch (\Throwable $Exception) {
                return $this->exceptionError($Exception);
            }
        }
        if (!($Customer instanceof Customer)) {
            $_error = sprintf('Failed to acquire customer.');
            return $this->exceptionError(new Exception($_error));
        }
        return $Customer;
    }

    /**
     * Get (or create) a charge.
     *
     * @since 17xxxx Stripe utils.
     *
     * @param string|array $args ID or args.
     *
     * @return Charge|Error Charge on success.
     */
    public function charge($args)
    {
        if (is_string($args)) {
            $args = ['charge' => $args];
        } // Convert charge ID into args.

        $default_args = [
            'api_key' => '', // Stripe API key.
            'charge'  => '', // Get existing ID.

            // Everything else is for a new charge.
            // A new charge requires a customer ID.
            'customer' => '', // Customer ID to charge.

            'amount'      => .50, // New charge amount.
            'currency'    => 'USD', // New charge currency.
            'description' => '', // New charge description.

            'order_id' => '', // New charge order ID.
            'metadata' => '', // New charge metadata.
        ];
        $args = (array) $args; // Force array.
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);
        extract($this->parseArgs(__FUNCTION__, $args));

        $Charge = null; // Initialize.

        if ($args['charge']) {
            try { // Acquire charge by ID.
                $Charge = Charge::retrieve($args['charge'], $opts);
            } catch (\Throwable $Exception) {
                return $this->exceptionError($Exception);
            }
        } else { // Create a new charge.
            try {
                $new_charge_args = [
                    'customer' => $args['customer'],

                    'amount'      => $args['amount'],
                    'currency'    => $args['currency'],
                    'description' => $args['description'] ?: null,

                    'metadata' => array_merge([
                        'order_id' => $args['order_id'],
                    ], $args['metadata']),
                ];
                $new_charge_args = $this->c::removeNulls($new_charge_args);
                $Charge          = Charge::create($new_charge_args, $opts);
            } catch (\Throwable $Exception) {
                return $this->exceptionError($Exception);
            }
        }
        if (!($Charge instanceof Charge)) {
            $_error = sprintf('Failed to acquire charge.');
            return $this->exceptionError(new Exception($_error));
        }
        return $Charge;
    }

    /**
     * Get (or create) a plan.
     *
     * @since 17xxxx Stripe utils.
     *
     * @param string|array $args ID or args.
     *
     * @return Plan|Error Plan on success.
     */
    public function plan($args)
    {
        if (is_string($args)) {
            $args = ['plan' => $args];
        } // Convert plan ID into args.

        $default_args = [
            'api_key' => '', // Stripe API key.
            'plan'    => '', // Get existing ID.

            // Everything else is for a new plan.

            'trial_period_days' => 0, // New plan trial.
            'interval'          => '', // New plan interval.
            // One of these: `day`, `week`, `month` or `year`.
            'interval_count' => '', // New plan interval count.
            // The number of intervals between each subscription billing.
            // For example, interval=month and interval_count=3, bills every 3 months.
            // Maximum of one year interval allowed (1 year, 12 months, 52 weeks, 365 days).

            'amount'   => .50, // New plan amount.
            'currency' => 'USD', // New plan currency.
            'name'     => '', // New plan name/description.

            'metadata' => '', // New charge metadata.
        ];
        $args = (array) $args; // Force array.
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);
        extract($this->parseArgs(__FUNCTION__, $args));

        $Plan = null; // Initialize.

        if ($args['plan']) {
            try { // Acquire plan by ID.
                $Plan = Plan::retrieve($args['plan'], $opts);
            } catch (\Throwable $Exception) {
                return $this->exceptionError($Exception);
            }
        } else { // Maybe create a new plan.
            $hash = 'pln_'.md5(serialize($args));

            try { // Check for existing plan.
                $Plan = Plan::retrieve($hash, $opts);
            } catch (\Throwable $Exception) {
                $Plan = null; // Soft failure.
            }
            if (!($Plan instanceof Plan)) {
                try { // New plan.
                    $new_plan_args = [
                        'id' => $hash, // Auto-generated ID.

                        'trial_period_days' => $args['trial_period_days'] ?: null,
                        'interval'          => $args['interval'],
                        'interval_count'    => $args['interval_count'],

                        'amount'   => $args['amount'],
                        'currency' => $args['currency'],
                        'name'     => $args['name'] ?: __('Subscription'),

                        'metadata' => $args['metadata'],
                    ];
                    $new_plan_args = $this->c::removeNulls($new_plan_args);
                    $Plan          = Plan::create($new_plan_args, $opts);
                } catch (\Throwable $Exception) {
                    return $this->exceptionError($Exception);
                }
            }
        }
        if (!($Plan instanceof Plan)) {
            $_error = sprintf('Failed to acquire plan.');
            return $this->exceptionError(new Exception($_error));
        }
        return $Plan;
    }

    /**
     * Get (or create) a subscription.
     *
     * @since 17xxxx Stripe utils.
     *
     * @param string|array $args ID or args.
     *
     * @return Subscription|Error Subscription on success.
     */
    public function subscription($args)
    {
        if (is_string($args)) {
            $args = ['subscription' => $args];
        } // Convert subscription ID into args.

        $default_args = [
            'api_key'       => '', // Stripe API key.
            'subscription'  => '', // Get existing ID.

            // Everything else is for a new subscription.
            // A new subscription requires a customer ID + plan.

            'customer' => '', // Customer ID to subscribe.
            'plan'     => '', // Plan ID to subscribe them to.

            'order_id' => '', // New subscription order ID.
            'metadata' => '', // New subscription metadata.
        ];
        $args = (array) $args; // Force array.
        $args = array_merge($default_args, $args);
        $args = array_intersect_key($args, $default_args);
        extract($this->parseArgs(__FUNCTION__, $args));

        $Subscription = null; // Initialize.

        if ($args['subscription']) {
            try { // Acquire subscription by ID.
                $Subscription = Subscription::retrieve($args['subscription'], $opts);
            } catch (\Throwable $Exception) {
                return $this->exceptionError($Exception);
            }
        } else { // Create a new subscription.
            try {
                $new_subscription_args = [
                    'customer' => $args['customer'],
                    'plan'     => $args['plan'],

                    'metadata' => array_merge([
                        'order_id' => $args['order_id'],
                    ], $args['metadata']),
                ];
                $new_subscription_args = $this->c::removeNulls($new_subscription_args);
                $Subscription          = Subscription::create($new_subscription_args, $opts);
            } catch (\Throwable $Exception) {
                return $this->exceptionError($Exception);
            }
        }
        if (!($Subscription instanceof Subscription)) {
            $_error = sprintf('Failed to acquire subscription.');
            return $this->exceptionError(new Exception($_error));
        }
        return $Subscription;
    }

    /**
     * Smallest currency unit.
     *
     * @since 17xxxx Stripe utils.
     *
     * @param float  $amount   Amount.
     * @param string $currency Currency code.
     *
     * @return int Amount represented as an integer (always).
     *
     * @see Units explained here <http://jas.xyz/2meM1pO>
     */
    protected function unitAmount(float $amount, string $currency): int
    {
        switch (mb_strtoupper($currency)) {
            case 'BIF':
            case 'DJF':
            case 'JPY':
            case 'KRW':
            case 'PYG':
            case 'VND':
            case 'XAF':
            case 'XPF':
            case 'CLP':
            case 'GNF':
            case 'KMF':
            case 'MGA':
            case 'RWF':
            case 'VUV':
            case 'XOF':
                return (int) $amount;

            default: // In cents.
                return (int) ($amount * 100);
        }
    }

    /**
     * Exception to error.
     *
     * @since 17xxxx Stripe utils.
     *
     * @param \Throwable $Exception Exception
     *
     * @return Classes\Core\Error An error object instance.
     */
    protected function exceptionError(\Throwable $Exception): Classes\Core\Error
    {
        if ($Exception instanceof StripeCardError) {
            $json  = (object) $Exception->getJsonBody();
            $error = (object) ($json->error ?? []);

            $code    = (string) ($error->code ?? 'error');
            $message = (string) ($error->message ?? '');

            if ($code && $message) { // If Stripe has given details.
                return $this->c::error('stripe-'.$code, $message, $json);
            }
        }
        return $this->c::error('stripe-error', __('Processing error, please try again.'), $Exception->getMessage());
    }

    /**
     * Argument parser.
     *
     * @since 17xxxx Stripe utils.
     *
     * @param string $func Function.
     * @param array  $args Request args.
     *
     * @return array ['args' => [], 'opts' => []]
     */
    protected function parseArgs(string $func, array $args): array
    {
        $default_api_key = $this->App->Config->©stripe['©api_key'];
        $api_key         = (string) ($args['api_key'] ?? '');
        $api_key         = $api_key ?: $default_api_key;
        unset($args['api_key']); // Ditch this now.

        // NOTE: The number `500` in clipping below comes
        // from a limit imposed by the Stripe API for metadata.

        foreach ($args as $_key => &$_value) {
            if ($_key === 'amount') {
                $_value    = (float) $_value;
                $_currency = (string) ($args['currency'] ?? '');
                $_currency = mb_substr(mb_strtoupper($_currency), 0, 3);
                $_value    = $this->unitAmount($_value, $_currency);
                //
            } elseif ($_key === 'currency') {
                $_value = mb_substr(mb_strtoupper((string) $_value), 0, 3);
                //
            } elseif ($_key === 'metadata') {
                if (is_object($_value)) {
                    $_value = (array) $_value;
                } elseif (!is_array($_value)) {
                    $_value = [];
                } // Forces an array value.

                foreach ($_value as $__key => &$__value) {
                    $__value = $this->c::clip((string) $__value, 500);
                } // Must unset value in sub-loop by reference.
                unset($__key, $__value); // Housekeeping.
                //
            } elseif (in_array($_key, ['trial_period_days', 'interval_count'], true)) {
                $_value = (int) $_value; // Integers.
                //
            } else {  // Everything else becomes a string.
                $_value = (string) $_value; // Force string value.
                if (!in_array($_key, ['customer', 'charge', 'subscription', 'plan', 'source', 'interval', 'currency'], true)) {
                    $_value = $this->c::clip($_value, 500); // Max of 500 chars.
                }
            }
        } // Must unset value by reference.
        unset($_key, $_value, $_currency); // Housekeeping.

        return $data = [
            'args' => $args,
            'opts' => [
                'api_key'        => $api_key,
                'stripe_version' => $this::API_VERSION,
            ],
        ];
    }
}
