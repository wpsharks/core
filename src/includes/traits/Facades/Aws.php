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
     * @since 170421.57490 AWS utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Aws::sdk()
     */
    public static function awsSdk(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Aws->sdk(...$args);
    }

    /**
     * @since 170421.57490 `awsSdk()` alias.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Aws::sdk()
     */
    public static function awsSdkConfig(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Aws->sdk(...$args);
    }

    /**
     * @since 170421.57490 AWS utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Aws::s3Client()
     */
    public static function awsS3Client(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Aws->s3Client($args);
    }

    /**
     * @since 170421.57490 AWS utilities.
     *
     * @param mixed ...$args Variadic args to underlying utility.
     *
     * @see Classes\Core\Utils\Aws::cloudFrontClient()
     */
    public static function awsCloudFrontClient(...$args)
    {
        return $GLOBALS[static::class]->Utils->©Aws->cloudFrontClient($args);
    }

    /**
     * @since 170421.57490 AWS utilities.
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
