<?php
/**
 * Request body.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core;

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
 * Request body.
 *
 * @since 17xxxx
 */
class RequestBody extends \Slim\Http\Body
{
    /**
     * App.
     *
     * @since 17xxxx
     *
     * @type Classes\App
     */
    protected $App;

    /**
     * Class constructor.
     *
     * @since 17xxxx Response utils.
     *
     * @param Classes\App $App     App.
     * @param string|null $content Content.
     */
    public function __construct(Classes\App $App, string $content = null)
    {
        $this->App = $App;
        parent::__construct(fopen('php://temp', 'r+'));

        if (isset($content)) {
            $this->write($content);
            $this->rewind();
        }
    }
}
