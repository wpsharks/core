<?php
declare (strict_types = 1);
namespace WebSharks\Core\Classes;

/**
 * CLI utilities.
 *
 * @since 150424 Initial release.
 */
class Cli extends AbsBase
{
    protected $StrCaseCmp;

    /**
     * Class constructor.
     *
     * @since 15xxxx Initial release.
     */
    public function __construct(
        StrCaseCmp $StrCaseCmp
    ) {
        parent::__construct();

        $this->StrCaseCmp = $StrCaseCmp;
    }

    /**
     * Running in a command line interface?
     *
     * @since 150424 Initial release.
     *
     * @return bool `TRUE` if running in a command line interface.
     */
    public function is(): bool
    {
        if (!is_null($is = &$this->staticKey(__FUNCTION__))) {
            return $is; // Cached this already.
        }
        if ($this->StrCaseCmp(PHP_SAPI, 'cli') === 0) {
            return ($is = true);
        }
        return ($is = false);
    }
}
