<?php
/**
 * AWS.
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
 * AWS.
 *
 * @since 160719
 */
trait Aws
{
    /**
     * @since 160719 AWS utilities.
     * @see Classes\Core\Utils\Aws::$Sdk
     */
    public static function awsSdk()
    {
        return $GLOBALS[static::class]->Utils->©Aws->Sdk;
    }

    /**
     * @since 160719 AWS utilities.
     * @see Classes\Core\Utils\Aws::$S3Client
     */
    public static function awsS3Client()
    {
        return $GLOBALS[static::class]->Utils->©Aws->S3Client;
    }

    /**
     * @since 17xxxx AWS utilities.
     * @see Classes\Core\Utils\Aws::$CloudFrontClient
     */
    public static function awsCloudFrontClient()
    {
        return $GLOBALS[static::class]->Utils->©Aws->CloudFrontClient;
    }

    /**
     * @since 17xxxx AWS utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Aws::cloudFrontSignUrl()
     */
    public static function awsCloudFrontSignUrl(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Aws->cloudFrontSignUrl(...$args);
    }
}
