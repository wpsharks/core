<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * MailChimp utilities.
 *
 * @since 160620 Adding MailChimp.
 */
class MailChimp extends Classes\Core\Base\Core
{
    /**
     * Class constructor.
     *
     * @since 160620 Adding MailChimp.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);
    }

    /**
     * Subscribe a user.
     *
     * @since 160620 Adding MailChimp.
     *
     * @param string $email Email address.
     * @param array  $args  API and request args.
     *
     * @return \StdClass Including a boolean `success` property.
     *
     * @see http://developer.mailchimp.com/documentation/mailchimp/reference/lists/members/
     */
    public function subscribe(string $email, array $args = []): \StdClass
    {
        # Parse incoming arguments.

        $default_args = [
            'list_id' => $this->App->Config->©mailchimp['©list_id'],
            'api_key' => $this->App->Config->©mailchimp['©api_key'],

            'email_address' => $email,
            'status'        => 'pending',
            /*
                'merge_fields'  => [
                    'FNAME' => '',
                    'LNAME' => '',
                ],
            */
            /*
                'ip_signup' => '', // If status = pending.
                'ip_opt'    => '', // If status = subscribed.
            */
        ];
        $args                  = array_merge($default_args, $args);
        $args['email_address'] = mb_strtolower($args['email_address']);

        # Prepare request args and endpoint URL.

        $request_data = $args; // Start w/ a copy of `$args`.
        unset($request_data['list_id'], $request_data['api_key']);

        $request_args = [
            'headers' => [
                'content-type: application/json',
                'authorization: Basic '.base64_encode('_:'.$args['api_key']),
            ],
            'body'            => json_encode($request_data),
            'max_con_secs'    => 5,
            'max_stream_secs' => 5,
            'fail_on_error'   => false,
            'return_array'    => true,
        ];
        $dc       = preg_replace('/^.+?\-/u', '', $args['api_key']);
        $endpoint = 'https://'.$dc.'.api.mailchimp.com/3.0/lists/'.urlencode($args['list_id']).'/members/';

        # Query for remote response via API endpoint URL.

        $response = $this->c::remoteRequest('POST::'.$endpoint, $request_args);

        # Return structured response data w/ `success` property.

        return (object) [
            'success'  => $response['code'] === 200,
            'data'     => json_decode($response['body']),
            'response' => $response,
        ];
    }

    /**
     * Get subscriber.
     *
     * @since 160620 Adding MailChimp.
     *
     * @param string $email Email address.
     * @param array  $args  API and request args.
     *
     * @return \StdClass Including a boolean `success` property.
     *
     * @see http://developer.mailchimp.com/documentation/mailchimp/reference/lists/members/
     */
    public function subscriber(string $email, array $args = []): \StdClass
    {
        # Parse incoming arguments.

        $default_args = [
            'list_id' => $this->App->Config->©mailchimp['©list_id'],
            'api_key' => $this->App->Config->©mailchimp['©api_key'],

            'email_address' => $email,
        ];
        $args                  = array_merge($default_args, $args);
        $args['email_address'] = mb_strtolower($args['email_address']);

        # Prepare request args and endpoint URL.

        $request_args = [
            'headers' => [
                'content-type: application/json',
                'authorization: Basic '.base64_encode('_:'.$args['api_key']),
            ],
            'max_con_secs'    => 5,
            'max_stream_secs' => 5,
            'fail_on_error'   => false,
            'return_array'    => true,
        ];
        $dc       = preg_replace('/^.+?\-/u', '', $args['api_key']);
        $endpoint = 'https://'.$dc.'.api.mailchimp.com/3.0/lists/'.urlencode($args['list_id']).'/members/'.md5($args['email_address']);

        # Query for remote response via API endpoint URL.

        $response = $this->c::remoteRequest('GET::'.$endpoint, $request_args);

        # Return structured response data w/ `success` property.

        return (object) [
            'success'  => $response['code'] === 200,
            'data'     => json_decode($response['body']),
            'response' => $response,
        ];
    }
}
