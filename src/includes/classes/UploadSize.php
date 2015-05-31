<?php
namespace WebSharks\Core\Classes;

/**
 * Upload size.
 *
 * @since 150424 Initial release.
 */
class UploadSize extends AbsBase
{
    protected $FsSize;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(
        FsSize $FsSize
    ) {
        parent::__construct();

        $this->FsSize = $FsSize;
    }

    /**
     * Max allowed file upload size.
     *
     * @since 150424 Initial release.
     *
     * @return float A floating point number.
     */
    public function limit()
    {
        $limits = array(PHP_INT_MAX); // Initialize.

        if (($max_upload_size = ini_get('upload_max_filesize'))) {
            $limits[] = $this->FsSize->abbrBytes($max_upload_size);
        }
        if (($post_max_size = ini_get('post_max_size'))) {
            $limits[] = $this->FsSize->abbrBytes($post_max_size);
        }
        if (($memory_limit = ini_get('memory_limit'))) {
            $limits[] = $this->FsSize->abbrBytes($memory_limit);
        }
        return ($max = min($limits));
    }
}
