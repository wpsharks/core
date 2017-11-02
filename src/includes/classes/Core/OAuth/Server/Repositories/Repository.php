<?php
/**
 * OAuth repository.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare(strict_types=1);
namespace WebSharks\Core\Classes\Core\OAuth\Server\Repositories;

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
 * OAuth repository.
 *
 * @since 17xxxx
 */
abstract class Repository extends Classes\Core\Base\Core
{
    /**
     * Class constructor.
     *
     * @since 17xxxx OAuth utils.
     *
     * @param Classes\App $App App.
     */
    public function __construct(Classes\App $App)
    {
        parent::__construct($App);
    }
}
