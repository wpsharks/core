<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Bitly utilities.
 *
 * @since 160102 Adding bitly.
 */
class Bitly extends Classes\AppBase
{
    /**
     * Class constructor.
     *
     * @since 160102 Adding bitly.
     */
    public function __construct()
    {
        parent::__construct();

        if (!$this->App->Config->bitly['api_key']) {
            throw new Exception('Missing API key.');
        }
    }

    /**
     * Shorten a URL.
     *
     * @since 160102 Adding bitly.
     *
     * @param string $long_url Long URL.
     *
     * @return string Short URL.
     */
    public function shorten(string $long_url): string
    {
        if (!$long_url) {
            return ''; // Nothing to do.
        }
        $endpoint = 'https://api-ssl.bitly.com/v3/shorten?access_token='.urlencode($this->App->Config->bitly['api_key']);
        $response = c\remote_request('GET::'.$endpoint.'&longUrl='.urlencode($long_url));

        if (!$response || !is_object($response = json_decode($response))) {
            return $long_url; // Unable to shorten.
        }
        if (empty($response->data->url)) {
            return $long_url;
        }
        return (string) $response->data->url;
    }
}
