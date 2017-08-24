<?php
/**
 * Upload size.
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

/**
 * Upload size.
 *
 * @since 150424 Initial release.
 */
class UploadSize extends Classes\Core\Base\Core
{
    /**
     * Max allowed file upload size.
     *
     * @since 150424 Initial release.
     * @since 170824.30708 Returns `int` instead of `float`.
     *
     * @return int Max allowed file upload size, in bytes.
     */
    public function limit(): int
    {
        $limits = [PHP_INT_MAX]; // Initialize.

        if (($max_upload_size = ini_get('upload_max_filesize'))) {
            $limits[] = $this->c::abbrToBytes($max_upload_size);
        }
        if (($post_max_size = ini_get('post_max_size'))) {
            $limits[] = $this->c::abbrToBytes($post_max_size);
        }
        if (($memory_limit = ini_get('memory_limit'))) {
            $limits[] = $this->c::abbrToBytes($memory_limit);
        }
        return $max = min($limits);
    }
}
