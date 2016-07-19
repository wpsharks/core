<?php
declare (strict_types = 1);
namespace WebSharks\Core\Traits\Facades;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Core\Base\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use function assert as debug;
use function get_defined_vars as vars;

trait Aws
{
    /**
     * @since 160719 AWS utilities.
     */
    public static function awsSdk()
    {
        return $GLOBALS[static::class]->Utils->©Aws->Sdk;
    }

    /**
     * @since 160719 AWS utilities.
     */
    public static function awsS3Client()
    {
        return $GLOBALS[static::class]->Utils->©Aws->S3Client;
    }
}
