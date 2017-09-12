<?php
/**
 * Request utils.
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
 * Request utils.
 *
 * @since 17xxxx
 */
class Request extends Classes\Core\Base\Core
{
    /**
     * Class constructor.
     *
     * @since 17xxxx Request utils.
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
     * Get JSON request body.
     *
     * @since 17xxxx Request utils.
     *
     * @return \StdClass JSON data.
     */
    public function jsonData(): \StdClass
    {
        if (!($data = file_get_contents('php://input'))) {
            return new \StdClass();
            //
        } elseif (!(($json = json_decode($data)) instanceof \StdClass)) {
            return new \StdClass();
        }
        return $json;
    }
}
