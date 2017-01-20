<?php
declare(strict_types=1);
namespace WebSharks\Core;

use WebSharks\Core\Classes\CoreFacades as c;

error_reporting(-1);
ini_set('display_errors', 'yes');
require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

c::dump($App);
