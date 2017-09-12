<?php
/**
 * Functions.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types=1);
namespace WebSharks\Core\Functions;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
#
use function assert as debug;
use function get_defined_vars as vars;

require_once __DIR__.'/i18n.php';
