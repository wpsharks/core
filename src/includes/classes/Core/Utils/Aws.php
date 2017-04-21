<?php
/**
 * AWS utilities.
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
use Aws\Sdk;
use Aws\S3\S3Client;
use Aws\CloudFront\CloudFrontClient;

/**
 * AWS utilities.
 *
 * @since 160719 AWS utilities.
 */
class Aws extends Classes\Core\Base\Core
{
    /**
     * Get SDK instance.
     *
     * @since 17xxxx SDK instance.
     *
     * @param array $args Instance args.
     *
     * @return Sdk SDK instance.
     */
    public function sdk(array $args = []): Sdk
    {
        if (($Sdk = &$this->cacheKey(__FUNCTION__, $args)) !== null) {
            return $Sdk; // Cached this already.
        }
        $default_args = [ // Global defaults.
            'version' => 'latest', // Dynamic.
            'region'  => $this->App->Config->©aws['©region'],

            'credentials' => [
                'key'    => $this->App->Config->©aws['©access_key'],
                'secret' => $this->App->Config->©aws['©secret_key'],
            ],
        ];
        $args       = array_replace_recursive($default_args, $args);
        return $Sdk = new Sdk($args); // Aws\Sdk instance.
    }

    /**
     * Get S3 client.
     *
     * @since 17xxxx SDK instance.
     *
     * @param array $args Instance args.
     *
     * @return S3Client Client instance.
     */
    public function s3Client(array $args = []): S3Client
    {
        if (($S3Client = &$this->cacheKey(__FUNCTION__, $args)) !== null) {
            return $S3Client; // Cached this already.
        }
        $default_args = [ // Global config defaults.
            'version' => $this->App->Config->©aws['©s3_version'],
        ];
        $args            = array_replace_recursive($default_args, $args);
        return $S3Client = $this->sdk()->createS3($args);
    }

    /**
     * Get CloudFront client.
     *
     * @since 17xxxx SDK instance.
     *
     * @param array $args Instance args.
     *
     * @return CloudFrontClient Client instance.
     */
    public function cloudFrontClient(array $args = []): CloudFrontClient
    {
        if (($CloudFrontClient = &$this->cacheKey(__FUNCTION__, $args)) !== null) {
            return $CloudFrontClient; // Cached this already.
        }
        $default_args = [ // Global config defaults.
            'version' => $this->App->Config->©aws['©cf_version'],
        ];
        $args                    = array_replace_recursive($default_args, $args);
        return $CloudFrontClient = $this->sdk()->createCloudFront($args);
    }

    /**
     * CloudFront URL signer.
     *
     * @since 17xxxx Magic overload handler.
     *
     * @param string $url           URL to sign.
     * @param int    $expires_after Expiration in seconds.
     * @param array  $args          Args to URL signer.
     * @param array  $client_args   Client instance args.
     *
     * @return string Digitally signed URL.
     */
    public function cloudFrontSignUrl(string $url, int $expires_after = 86400, array $args = [], array $client_args = []): string
    {
        $default_args = [ // Global config defaults.
            'key_pair_id' => $this->App->Config->©aws['©cf_key_pair_id'],
            'private_key' => $this->App->Config->©aws['©cf_private_key_file'],
        ];
        $args            = array_replace_recursive($default_args, $args);
        $args['url']     = $url; // `url` and `expires` via params.
        $args['expires'] = time() + max(0, $expires_after);

        $CloudFrontClient  = $this->cloudFrontClient($client_args);
        return $signed_url = $CloudFrontClient->getSignedUrl($args);
    }
}
