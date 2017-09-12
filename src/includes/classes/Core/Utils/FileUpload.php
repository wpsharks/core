<?php
/**
 * File upload utilities.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * File upload utilities.
 *
 * @since 150424 Initial release.
 */
class FileUpload extends Classes\Core\Base\Core
{
    /**
     * Error reason.
     *
     * @since 160926 Upload utils.
     *
     * @param int $code An upload error code.
     *
     * @return string An upload error reason.
     */
    public function errorReason(int $code): string
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
                return __('Exceeds `upload_max_filesize` directive in `php.ini`.');

            case UPLOAD_ERR_FORM_SIZE:
                return __('Exceeds `MAX_FILE_SIZE` directive in the HTML form.');

            case UPLOAD_ERR_PARTIAL:
                return __('Data missing; partial.');

            case UPLOAD_ERR_NO_FILE:
                return __('Missing file.');

            case UPLOAD_ERR_NO_TMP_DIR:
                return __('Missing temp dir.');

            case UPLOAD_ERR_CANT_WRITE:
                return __('Failed to write file to disk.');

            case UPLOAD_ERR_EXTENSION:
                return __('A PHP extension stopped the file upload.');

            default: // Unknown error code.
                return __('Unknown error.');
        }
    }
}
