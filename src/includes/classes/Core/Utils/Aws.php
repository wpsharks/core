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
     * AWS S3 client.
     *
     * @since 160719
     *
     * @type AwsLib\S3\S3Client
     */
    public $S3Client;

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
            'version' => 'latest', // Default.
            'region'  => $this->App->Config->©aws['©region'],

            'credentials' => [
                'key'    => $this->App->Config->©aws['©access_key'],
                'secret' => $this->App->Config->©aws['©secret_key'],
            ],
        ]);
        $this->S3Client = $this->Sdk->createS3([
            'version' => $this->App->Config->©aws['©s3_version'],
        ]);
        // A quick example of how this can be used in PHP.
        // $this->S3Client->registerStreamWrapper();
        // See: <http://docs.aws.amazon.com/aws-sdk-php/v3/guide/service/s3-stream-wrapper.html>
    }
}
