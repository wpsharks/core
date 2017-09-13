<?php
/**
 * Response utils.
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
 * Response utils.
 *
 * @since 17xxxx
 */
class Response extends Classes\Core\Base\Core
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
     * Create response.
     *
     * @since 17xxxx Request utilities.
     *
     * @return Classes\Core\Response Instance.
     */
    public function create(array $args = []): Classes\Core\Response
    {
        return $this->App->Di->get(Classes\Core\Response::class, $args);
    }
}
