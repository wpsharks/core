<?php
declare (strict_types = 1);
namespace WebSharks\Core;

use WebSharks\Core\Classes\CoreFacades as c;
use function assert as debug;

error_reporting(-1);
ini_set('display_errors', 'yes');
require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

(function () use ($App) {
    debug(0, c::issue($App, 'Quick test.'));
})(); // Exception w/ `$App` logged as an issue.
// Turning off assertions in PHP will bypass debugging.
