<?php
/**
 * Spin & reload.
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
 * Spin & reload.
 *
 * @since 17xxxx Spin & reload.
 */
class SpinReload extends Classes\Core\Base\Core
{
    /**
     * Spin & reload.
     *
     * @since 17xxxx Spin & reload.
     *
     * @param array $vars Template vars.
     */
    public function __invoke(array $vars = [])
    {
        $this->c::noCacheFlags();
        $this->c::noCacheHeaders();

        header('content-type: text/html; charset=utf-8');
        exit($this->c::getTemplate('http/html/utils/spin-reload.php')->parse($vars));
    }
}
