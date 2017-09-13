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
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
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
     * Current request.
     *
     * @since 17xxxx Request utilities.
     *
     * @return Classes\Core\Request Instance.
     */
    public function current(): Classes\Core\Request
    {
        return Classes\Core\Request::createFromGlobals($_SERVER);
    }

    /**
     * Create request.
     *
     * @since 17xxxx Request utilities.
     *
     * @return Classes\Core\Request Instance.
     */
    public function create(array $args = []): Classes\Core\Request
    {
        return $this->App->Di->get(Classes\Core\Request::class, $args);
    }
}
