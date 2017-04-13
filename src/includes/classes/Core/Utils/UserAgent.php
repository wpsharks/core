<?php
/**
 * User-Agent utils.
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
 * User-Agent utils.
 *
 * @since 170413.34876 User-Agent utils.
 */
class UserAgent extends Classes\Core\Base\Core
{
    /**
     * Class constructor.
     *
     * @since 170413.34876 User-Agent utils.
     *
     * @param Classes\App $App Instance of App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);

        if ($this->c::isCli()) {
            throw $this->c::issue('Not possible in CLI mode.');
        }
    }

    /**
     * Is a browser engine.
     *
     * @since 170413.34876 User-Agent utils.
     *
     * @return bool True if is engine.
     *
     * @see https://jas.xyz/2p3VIbG
     */
    public function isEngine(): bool
    {
        if (($is = &$this->cacheKey(__FUNCTION__)) !== null) {
            return $is; // Cached this already.
        }
        $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        return $is  = $user_agent && preg_match('/(?:blink|gecko|konqueror|msie|opera|playstation|presto|trident|webkit)/ui', $user_agent);
    }
}
