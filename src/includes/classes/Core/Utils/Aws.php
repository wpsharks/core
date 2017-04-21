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
use Aws as AwsLib;

/**
 * AWS utilities.
 *
 * @since 160719 AWS utilities.
 */
class Aws extends Classes\Core\Base\Core
{
    /**
     * AWS SDK.
     *
     * @since 160719
     *
     * @type AwsLib\Sdk
     */
    public $Sdk;

    /**
     * AWS clients.
     *
     * @since 17xxxx
     *
     * @type \StdClass
     */
    protected $clients;

    /**
     * Constructor.
     *
     * @since 160719 Initial release.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        $this->Sdk = new AwsLib\Sdk([
            'version' => 'latest', // Default version.
            'region'  => $this->App->Config->©aws['©region'],

            'credentials' => [
                'key'    => $this->App->Config->©aws['©access_key'],
                'secret' => $this->App->Config->©aws['©secret_key'],
            ],
        ]);
        $this->clients = (object) [];
    }

    /**
     * Magic/overload property getter.
     *
     * @since 17xxxx Magic overload handler.
     *
     * @param string $property Property to get.
     *
     * @return mixed AWS client else parent return value.
     */
    public function __get(string $property)
    {
        if ($property === 'S3Client') {
            return $this->clients->S3Client = $this->clients->S3Client ?? $this->Sdk->createS3([
                'version' => $this->App->Config->©aws['©s3_version'],
            ]);
        } elseif ($property === 'CloudFrontClient') {
            return $this->clients->CloudFrontClient = $this->clients->CloudFrontClient ?? $this->Sdk->createCloudFront([
                'version' => $this->App->Config->©aws['©cf_version'],
            ]);
        }
        return parent::__get($property);
    }

    /**
     * CloudFront URL signer.
     *
     * @since 17xxxx Magic overload handler.
     *
     * @param string $url           URL to sign.
     * @param int    $expires_after Expiration in seconds.
     *
     * @return string Signed URL.
     */
    public function cloudFrontSignUrl(string $url, int $expires_after = 86400): string
    {
        $expires = time() + max(0, $expires_after);

        try { // Catch and rethrow exceptions.
            return $this->CloudFrontClient->getSignedUrl([
                'url'         => $url,
                'expires'     => $expires,
                'key_pair_id' => $this->App->Config->©aws['©cf_key_pair_id'],
                'private_key' => $this->App->Config->©aws['©cf_private_key_file'],
            ]);
        } catch (\Throwable $Exception) {
            throw $this->c::issue(vars(), $Exception->getMessage());
        }
    }
}
