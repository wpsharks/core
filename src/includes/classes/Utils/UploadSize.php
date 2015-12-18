<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Classes\Exception;
use WebSharks\Core\Functions as c;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;

/**
 * Upload size.
 *
 * @since 150424 Initial release.
 */
class UploadSize extends Classes\AppBase
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
        $limits = array(PHP_INT_MAX); // Initialize.

        if (($max_upload_size = ini_get('upload_max_filesize'))) {
            $limits[] = c\abbr_to_bytes($max_upload_size);
        }
        if (($post_max_size = ini_get('post_max_size'))) {
            $limits[] = c\abbr_to_bytes($post_max_size);
        }
        if (($memory_limit = ini_get('memory_limit'))) {
            $limits[] = c\abbr_to_bytes($memory_limit);
        }
        return $max = min($limits);
    }
}
