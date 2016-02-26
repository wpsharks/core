<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Upload size.
 *
 * @since 150424 Initial release.
 */
class UploadSize extends Classes\Core
{
    /**
     * Max allowed file upload size.
     *
     * @since 150424 Initial release.
     *
     * @return float A floating point number.
     */
    public function limit(): float
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
